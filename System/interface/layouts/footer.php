            </div>
            <!-- /page-content -->
            
        </main>
        <!-- /main-content -->
        
    </div>
    <!-- /app-wrapper -->


<script>
    // Toggle Sidebar - Responsive
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.getElementById('sidebar');

    if (sidebar) {
        // Restaurar estado del sidebar desde localStorage
        const sidebarState = localStorage.getItem('sidebarCollapsed') === 'true';
        if (sidebarState) {
            sidebar.classList.add('collapsed');
        }

        // Toggle al hacer click
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');

                // Guardar estado en localStorage
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            });
        }

        // En mobile, colapsar por defecto (width < 768px)
        if (window.innerWidth < 768 && !sidebarState) {
            sidebar.classList.add('collapsed');
            localStorage.setItem('sidebarCollapsed', 'true');
        }
    }

    // Toggle Catálogo submenu (compatibilidad Edge/Chrome)
    const catalogMenu = document.getElementById('catalogMenu');
    const catalogMenuToggle = catalogMenu ? catalogMenu.querySelector('.menu-toggle') : null;
    const catalogSubmenu = document.getElementById('catalogSubmenu');

    if (catalogMenu && catalogMenuToggle) {
        // Detectar si estamos en una página del catálogo
        const isInCatalogPage = catalogSubmenu && catalogSubmenu.querySelector('a.active') !== null;

        if (isInCatalogPage) {
            // En catálogo: mantener abierto
            catalogMenu.classList.add('open');
            localStorage.setItem('catalogMenuOpen', 'true');
        } else {
            // Fuera de catálogo: cerrar automático
            catalogMenu.classList.remove('open');
            localStorage.setItem('catalogMenuOpen', 'false');
        }

        // Toggle manual siempre disponible
        catalogMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();

            // Si el sidebar está colapsado, expandir primero
            if (sidebar && sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('collapsed');
                localStorage.setItem('sidebarCollapsed', 'false');
            }

            catalogMenu.classList.toggle('open');
            const isOpen = catalogMenu.classList.contains('open');
            localStorage.setItem('catalogMenuOpen', isOpen ? 'true' : 'false');
        });
    }

</script>

</body>
</html>
