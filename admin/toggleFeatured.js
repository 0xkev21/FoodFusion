document.querySelectorAll('.featured-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
      const recipeId = this.getAttribute('data-id');
      const isFeatured = this.checked ? 1 : 0;

      const formData = new FormData();
      formData.append('id', recipeId);
      formData.append('status', isFeatured);

      fetch('api/toggleFeatured.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            console.log('Status updated');
          } else {
            alert('Error updating status');
            this.checked = !this.checked;
          }
        })
        .catch(error => {
          console.error('Error:', error);
          this.checked = !this.checked;
        });
    });
  });