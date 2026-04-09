import { io } from 'socket.io-client'

export default defineNuxtPlugin(() => {
    let socketUrl = 'http://localhost:3000'

    if (import.meta.client) {
        socketUrl = window.location.hostname === 'localhost'
            ? 'http://localhost:3000'
            : window.location.origin
    }

    const socket = io(socketUrl, {
        autoConnect: true,
        transports: ['websocket'],
        reconnectionAttempts: 10,
        reconnectionDelay: 2000
    })

    if (import.meta.client) {
        socket.on('connect', () => console.log('✅ Connected to WebSocket'))
        socket.on('disconnect', () => console.log('❌ Disconnected from WebSocket'))
    }

    return {
        provide: {
            socket
        }
    }
})
