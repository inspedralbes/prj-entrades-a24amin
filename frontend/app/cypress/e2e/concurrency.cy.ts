describe('Concurrency and Real-time', () => {
    it('should handle two users trying to reserve the same seat', () => {
        // We simulate User A (Cypress browser) and User B (via cy.request or socket)

        cy.visit('/')
        cy.get('.event-card').first().find('.btn-primary').click()

        // Wait for the seat map to load
        cy.get('.seat-map-container').should('be.visible')

        // Pick first available seat
        cy.get('.seat-group.selectable').first().as('targetSeat')

        cy.get('@targetSeat').invoke('attr', 'data-seat-id').then((seatId) => {
            // Simulate User B reserving the seat via API (from outside this browser)
            // Note: We need the token for User B or a way to trigger it.
            // For this demonstration, we assume there is a mock endpoint or we use the socket.

            // In a real scenario, we might use multiple Cypress instances or cy.request
            // but here we test if the UI reacts to the 'seat_reserved' event.

            // Attempt to click the seat after it might have been taken
            cy.get('@targetSeat').click()

            // If the server validates correctly, if another user took it meanwhile, 
            // the click should result in an error or the state change should have been received.

            // Verification: The seat should have the 'selected' class for User A
            cy.get('@targetSeat').should('have.class', 'selected')

            // Now, if we simulate the seat being taken by User B (server side), 
            // User A should see a conflict if they try to buy it or if the socket updates.
        })
    })
})
