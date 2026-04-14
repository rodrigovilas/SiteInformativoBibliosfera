document.addEventListener('DOMContentLoaded', () => {
    // Barra de Progresso de Leitura
    const progressBar = document.getElementById('reading-progress');
    
    if (progressBar) {
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            progressBar.style.width = scrolled + "%";
        });
    }

    // Suavizar scroll para links internos
    document.querySelectorAll('a[data-scroll]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80, // Offset do header fixo
                    behavior: 'smooth'
                });
            }
        });
    });

    // Animações simples ao rolar
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.card, .artigo-corpo h3, .livro-resenha').forEach(el => {
        el.style.opacity = "0";
        el.style.transition = "all 0.6s ease-out";
        el.style.transform = "translateY(20px)";
        observer.observe(el);
    });

    // Adiciona classe para disparar animação CSS
    window.addEventListener('scroll', () => {
        document.querySelectorAll('.card, .artigo-corpo h3, .livro-resenha').forEach(el => {
            const rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight - 100) {
                el.style.opacity = "1";
                el.style.transform = "translateY(0)";
            }
        });
    });
});
