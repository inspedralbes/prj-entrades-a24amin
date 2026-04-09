describe('Homepage', () => {
    it('should load the homepage and show events', () => {
        cy.visit('/')
        cy.get('h1').should('contain', 'Shôko Cinema')
        cy.get('.event-card').should('have.length.at.least', 1)
    })

    it('should navigate to an event page', () => {
        cy.visit('/')
        cy.get('.event-card').first().find('.btn-primary').click()
        cy.url().should('include', '/events/')
        cy.get('.seat-map').should('be.visible')
    })
})
