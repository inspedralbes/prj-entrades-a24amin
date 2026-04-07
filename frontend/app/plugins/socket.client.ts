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
        transports: ['websocket']
    })

    return {
        provide: {
            socket
        }
    }
})
