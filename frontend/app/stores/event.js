import { defineStore } from 'pinia'

export const useEventStore = defineStore('event', {
    state: () => ({
        currentEvent: null,
        selectedSeats: [],
        reservationTimer: null,
        timeLeft: 0,
        loading: false,
        error: null
    }),

    actions: {
        async fetchEvent(eventId) {
            // Only clear if we are switching to a DIFFERENT movie
            if (this.currentEvent?.id !== parseInt(eventId)) {
                this.clearSelection()
            }
            this.loading = true
            const config = useRuntimeConfig()
            const { $socket } = useNuxtApp()

            try {
                const data = await $fetch(`${config.public.apiBase}/events/${eventId}`)
                this.currentEvent = data

                // Join the socket room for this event
                $socket.emit('join_event', eventId)

                // Listen for updates if not already listening
                $socket.off('seat_updated')
                $socket.on('seat_updated', (update) => {
                    this.updateSeat(update)
                })

                // Re-unir-se a la sala si es perd la connexió i es recupera
                $socket.on('connect', () => {
                    if (this.currentEvent) {
                        $socket.emit('join_event', this.currentEvent.id)
                        console.log('Re-unit a la sala', this.currentEvent.id)
                    }
                })

            } catch (err) {
                this.error = 'Error al carregar l\'esdeveniment'
                console.error(err)
            } finally {
                this.loading = false
            }
        },

        updateSeat(update) {
            if (!this.currentEvent) return

            this.currentEvent.zones.forEach(zone => {
                const seat = zone.seats.find(s => s.id === update.seat_id)
                if (seat) {
                    seat.status = update.status

                    // If the seat was selected by me but now is reserved by someone else or sold
                    if (update.status !== 'available' && update.user_id != useAuthStore().user?.id) {
                        this.selectedSeats = this.selectedSeats.filter(s => s.id !== update.seat_id)
                    }
                }
            })
        },

        async toggleSeat(seat) {
            const auth = useAuthStore()
            if (!auth.isLoggedIn) {
                alert('Has d\'iniciar sessió per reservar seients.')
                return
            }

            // Guard: No processis si el seient està reservat per un altre o ocupat
            const isSelected = this.selectedSeats.find(s => s.id === seat.id)
            if (seat.status !== 'available' && !isSelected) {
                return
            }

            const config = useRuntimeConfig()

            try {
                if (isSelected) {
                    // Release seat
                    await $fetch(`${config.public.apiBase}/release`, {
                        method: 'POST',
                        body: { seat_id: seat.id, event_id: this.currentEvent.id },
                        headers: { Authorization: `Bearer ${auth.token}` }
                    })
                    this.selectedSeats = this.selectedSeats.filter(s => s.id !== seat.id)
                } else {
                    // Limit selection (e.g. max 6 seats)
                    if (this.selectedSeats.length >= 6) {
                        alert('Només pots reservar fins a 6 seients.')
                        return
                    }

                    // Reserve seat
                    const response = await $fetch(`${config.public.apiBase}/reserve`, {
                        method: 'POST',
                        body: { seat_id: seat.id, event_id: this.currentEvent.id },
                        headers: { Authorization: `Bearer ${auth.token}` }
                    })

                    // Verify if it's still not selected (double check)
                    if (!this.selectedSeats.find(s => s.id === seat.id)) {
                        this.selectedSeats.push(seat)
                    }
                    this.startTimer(response.expires_in)
                }
            } catch (err) {
                // Només mostrem alerta si realment hem intentat reservar un seient que crèiem lliure
                if (!isSelected && seat.status === 'available') {
                    alert(err.data?.message || 'Error al processar la reserva.')
                }
                console.error('Toggle seat error:', err)
            }
        },

        startTimer(seconds) {
            this.timeLeft = seconds
            if (this.reservationTimer) clearInterval(this.reservationTimer)

            this.reservationTimer = setInterval(() => {
                if (this.timeLeft > 0) {
                    this.timeLeft--
                } else {
                    this.clearSelection()
                }
            }, 1000)
        },

        clearSelection() {
            if (this.reservationTimer) clearInterval(this.reservationTimer)
            this.selectedSeats = []
            this.timeLeft = 0
        }
    }
})
