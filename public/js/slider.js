
        console.log("Slider.js chargé chef!");
        let currentSlide = 1;
        let slideInterval;
        let progressInterval;
        const totalSlides = 3;
        const slideDuration = 8000;

        function showSlide(slideNumber) {
            document.querySelectorAll('.hero-slide').forEach(slide => {
                slide.style.display = 'none';
            });
            
            document.querySelector('[data-slide="' + slideNumber + '"]').style.display = 'block';
            
            document.querySelectorAll('.slide-dot').forEach(dot => {
                dot.style.background = 'transparent';
                dot.classList.remove('active');
            });
            
            const activeDot = document.querySelector('[data-slide="' + slideNumber + '"].slide-dot');
            if (activeDot) {
                activeDot.style.background = 'white';
                activeDot.classList.add('active');
            }
            
            currentSlide = slideNumber;
        }

        function nextSlide() {
            const next = currentSlide >= totalSlides ? 1 : currentSlide + 1;
            showSlide(next);
        }

        function startSlider() {
            const progressBar = document.getElementById('progress-bar');
            if (progressBar) {
                progressBar.style.width = '0%';
                
                let progress = 0;
                progressInterval = setInterval(() => {
                    progress += 100 / (slideDuration / 100);
                    progressBar.style.width = progress + '%';
                }, 100);
            }
            
            slideInterval = setTimeout(() => {
                nextSlide();
                startSlider();
            }, slideDuration);
        }

        document.addEventListener('DOMContentLoaded', function() {
            showSlide(1);
            startSlider();
            
            document.querySelectorAll('.slide-dot').forEach(dot => {
                dot.addEventListener('click', () => {
                    clearTimeout(slideInterval);
                    clearInterval(progressInterval);
                    showSlide(parseInt(dot.dataset.slide));
                    startSlider();
                });
            });
            
            const slider = document.getElementById('hero-slider');
            if (slider) {
                slider.addEventListener('mouseenter', () => {
                    clearTimeout(slideInterval);
                    clearInterval(progressInterval);
                });
                
                slider.addEventListener('mouseleave', () => {
                    startSlider();
                });
            }
        });