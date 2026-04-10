import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useEventStore } from '../stores/event'

// Mock de Nuxt composables i plugins
vi.stubGlobal('useRuntimeConfig', () => ({ public: { apiBase: 'http://localhost/api' } }))
vi.stubGlobal('useNuxtApp', () => ({ $socket: { on: vi.fn(), off: vi.fn(), emit: vi.fn() } }))
vi.stubGlobal('useAuthStore', () => ({ user: { id: 1 }, token: 'abc' }))

describe('Event Store', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
    })

    it('inicialitza amb l\'estat per defecte', () => {
        const store = useEventStore()
        expect(store.currentEvent).toBeNull()
        expect(store.selectedSeats).toEqual([])
        expect(store.timeLeft).toBe(0)
    })

    it('clearSelection reinicia la selecció i el temporitzador', () => {
        const store = useEventStore()
        store.selectedSeats = [{ id: 1 }]
        store.timeLeft = 100

        store.clearSelection()

        expect(store.selectedSeats).toEqual([])
        expect(store.timeLeft).toBe(0)
    })

    it('startTimer decrementa el temps restant', async () => {
        vi.useFakeTimers()
        const store = useEventStore()

        store.startTimer(10)
        expect(store.timeLeft).toBe(10)

        vi.advanceTimersByTime(1000)
        expect(store.timeLeft).toBe(9)

        vi.useRealTimers()
    })

    it('updateSeat actualitza l\'estat d\'un seient al store', () => {
        const store = useEventStore()
        store.currentEvent = {
            id: 1,
            zones: [{
                id: 1,
                seats: [{ id: 10, status: 'available' }]
            }]
        }

        store.updateSeat({ seat_id: 10, status: 'reserved' })

        expect(store.currentEvent.zones[0].seats[0].status).toBe('reserved')
    })
})
