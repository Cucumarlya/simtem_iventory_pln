// Master Material Index JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initializeSearch();
    initializeDeleteModal();
});

// Search functionality
function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.material-row');
            
            rows.forEach(row => {
                const materialName = row.getAttribute('data-name');
                const materialCode = row.getAttribute('data-code');
                
                if (materialName.includes(searchTerm) || materialCode.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
}

// Delete Modal functionality
function showDeleteModal(materialId, materialName) {
    document.getElementById('deleteMaterialName').textContent = materialName;
    document.getElementById('deleteForm').action = `/material/${materialId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function hideDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function initializeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                hideDeleteModal();
            }
        });
    }

    // Escape key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideDeleteModal();
        }
    });
}

// Export functions for global access
window.showDeleteModal = showDeleteModal;
window.hideDeleteModal = hideDeleteModal;