<template>
  <footer class="footer">
    <div class="container">
      <p>&copy; {{ new Date().getFullYear() }} AminPassMaster. DAW 2 Project.</p>
    </div>
  </footer>
</template>

<style scoped>
.footer {
  background-color: #000;
  color: #666;
  padding: 3rem 0;
  text-align: center;
  border-top: 1px solid #222;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
}
</style>
