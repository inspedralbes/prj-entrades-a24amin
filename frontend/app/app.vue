<script setup>
// Podem treure NuxtWelcome per començar a veure la nostra app
</script>

<template>
  <div class="app-container">
    <NuxtRouteAnnouncer />
    
    <header>
      <h1>Ticket Platform</h1>
      <nav>
        <a href="#">Inici</a>
        <a href="#">Esdeveniments</a>
        <a href="#">Login</a>
      </nav>
    </header>

    <main>
      <section class="hero">
        <h2>Benvingut a la plataforma de venda en temps real</h2>
        <p>Selecciona el teu esdeveniment i compra les teves entrades de forma segura.</p>
        <button class="btn-primary">Veure Esdeveniments</button> section>
      </section>
    </main>
  </div>
</template>

<style scoped>
.app-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

header {
  padding: 1rem 2rem;
  background-color: var(--color-3);
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

nav a {
  color: white;
  margin-left: 1rem;
  text-decoration: none;
}

.hero {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 4rem;
  text-align: center;
}

.btn-primary {
  background-color: var(--color-5);
  color: white;
  padding: 0.8rem 1.5rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  margin-top: 1rem;
}

.btn-primary:hover {
  filter: brightness(1.2);
}
</style>
