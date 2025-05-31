import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    // For authenticated users - toggling favorite status
    const favoriteButtons = document.querySelectorAll('.favorite-btn');
    favoriteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const houseId = this.dataset.houseId;
            const icon = this.querySelector('i'); // Get the <i> element inside the button

            if (!houseId || !icon) {
                console.error('House ID or icon element not found for favorite button.');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/favorites/${houseId}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                // No body is needed for this toggle operation as per the controller
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'added') {
                    icon.classList.remove('far');
                    icon.classList.add('fas', 'text-red-500'); // Standard filled red heart

                    // Remove old empty state colors
                    icon.classList.remove('text-white', 'text-gray-600', 'text-gray-500'); 

                } else if (data.status === 'removed') {
                    icon.classList.remove('fas', 'text-red-500');
                    icon.classList.add('far');

                    // Determine which empty state color to apply based on button style
                    if (button.classList.contains('bg-blue-600')) { // Card view button
                        icon.classList.add('text-white');
                        icon.classList.remove('text-gray-600', 'text-gray-500');
                    } else { // Details view button (or other white background buttons)
                        icon.classList.add('text-gray-600');
                        icon.classList.remove('text-white', 'text-red-500'); // ensure red is removed
                    }
                } else {
                    console.error('Unexpected response status:', data.status, data.message);
                    // Optionally, display an error message to the user
                }
            })
            .catch(error => {
                console.error('Error toggling favorite:', error);
                // Optionally, display an error message to the user (e.g., "Could not update favorite status. Please try again.")
            });
        });
    });
});
