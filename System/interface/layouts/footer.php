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
</script>

</body>
</html>
