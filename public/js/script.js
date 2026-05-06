document.querySelectorAll('.star-icon').forEach(star => {
    star.addEventListener('click', function() {
        let val = this.getAttribute('data-value');
        let parent = this.closest('.d-flex');
        
        // Reset semua bintang ke abu-abu
        parent.querySelectorAll('.star-icon').forEach(s => {
            s.classList.remove('text-warning', 'fa-solid');
            s.classList.add('text-muted', 'fa-regular');
        });

        // Warnai bintang sampai urutan yang diklik
        for(let i = 0; i < val; i++) {
            let s = parent.querySelectorAll('.star-icon')[i];
            s.classList.remove('text-muted', 'fa-regular');
            s.classList.add('text-warning', 'fa-solid');
        }
    });
});