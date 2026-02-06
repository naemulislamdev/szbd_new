   //multi social toggle and drag and drop
   document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.getElementById('chat-wrapper');
            const toggleBtn = document.getElementById('chatToggle');
            const icon = toggleBtn.querySelector('i');

            // Toggle chat box
            toggleBtn.addEventListener('click', (e) => {
                wrapper.classList.toggle('active');
                if (wrapper.classList.contains('active')) {
                    icon.classList.remove('bi-chat-right-text');
                    icon.classList.add('bi-x-circle');
                } else {
                    icon.classList.remove('bi-x-circle');
                    icon.classList.add('bi-chat-right-text');
                }
            });
        });

        //end
         $(document).ready(function() {
            $('.new-arrivals-section .owl-carousel').each(function() {

                $(this).owlCarousel({
                    loop: true,
                    margin: 10,
                    autoplay: false,
                    autoplayTimeout: 3000,
                    autoplayHoverPause: true,
                    smartSpeed: 500,
                    nav: true,
                    navText: [
                        '<i class="fa fa-chevron-left text-white"></i>',
                        '<i class="fa fa-chevron-right text-white"></i>'
                    ],
                    responsive: {
                        0: {
                            items: 2
                        },
                        768: {
                            items: 3
                        },
                        992: {
                            items: 6
                        }
                    }
                });
            });
        });
        //end

         $(document).ready(function() {
            $('.owl-carousel.trending-carousel').each(function() {

                $(this).owlCarousel({
                    loop: true,
                    margin: 10,
                    autoplay: true,
                    autoplayTimeout: 2000,
                    smartSpeed: 500,
                    nav: true,
                    dots: false,
                    autoplayHoverPause: true,
                    responsiveRefreshRate: 0,
                    navText: [
                        '<i title="Prev" class="fa fa-chevron-left"></i>',
                        '<i title="Next" class="fa fa-chevron-right "></i>'
                    ],
                    responsive: {
                        0: {
                            margin: 10,
                            items: 2
                        },
                        768: {
                            items: 2
                        },
                        992: {
                            items: 4
                        }
                    }
                });
            });
        });
        //end

         $(document).ready(function() {
            $('.owl-carousel.category-carosel').each(function() {

                $(this).owlCarousel({
                    loop: true,
                    margin: 20,
                    autoplay: true,
                    autoplayTimeout: 3000,
                    autoplayHoverPause: true,
                    smartSpeed: 500,
                    nav: true,
                    dots: false,
                    responsiveRefreshRate: 0,
                    navText: [
                        '<i title="Prev" class="fa fa-chevron-left text-white"></i>',
                        '<i title="Next" class="fa fa-chevron-right text-white"></i>'
                    ],
                    responsive: {
                        0: {
                            margin: 10,
                            items: 3
                        },
                        768: {
                            items: 3
                        },
                        992: {
                            items: 6
                        }
                    }
                });
            });
        });
        // end

         $(document).ready(function() {
            /*mobile menu*/
            $('.menu-icon').on('click', function() {
                $('.mobile-menu').toggleClass('mobile-menu-active');
            });
            $('.mm-ci').on('click', function() {
                $('.mobile-menu').toggleClass('mobile-menu-active');
            });


        });
        //End

         // Store the original title Start
        let originalTitle = document.title;
        let scrollTitle = "We miss you! Please come back soon.";
        let timeout;
        let scrollTimeout;
        let position = 0;

        // Function to scroll the title
        function scrollText() {
            document.title = scrollTitle.substring(position) + " " + scrollTitle.substring(0, position);
            position++;
            if (position > scrollTitle.length) {
                position = 0;
            }
            scrollTimeout = setTimeout(scrollText, 200); // Adjust the speed by changing the timeout
        }
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                // When the user leaves the tab, wait for 10 seconds before changing the title
                timeout = setTimeout(function() {
                    scrollText(); // Start scrolling the text
                }, 2000); // 10-second delay
            } else {
                // When the user comes back, clear the timeouts and revert to the original title
                clearTimeout(timeout);
                clearTimeout(scrollTimeout);
                document.title = originalTitle;
                position = 0;
            }
        });
        //End

         $(document).ready(function() {
            // Add minus icon for collapse element which is open by default
            $(".collapse.show").each(function() {
                $(this).prev(".menu-link").find(".fa-minus").addClass("fa-minus").removeClass("fa-plus");
            });

            // Toggle plus minus icon on show hide of collapse element
            $(".collapse").on('show.bs.collapse', function() {
                $(this).prev(".menu-link").find(".fa-plus").removeClass("fa-plus").addClass("fa-minus");
            }).on('hide.bs.collapse', function() {
                $(this).prev(".menu-link").find(".fa-minus").removeClass("fa-minus").addClass("fa-plus");
            });
            /*mobile-menu-click*/
            $('.mmenu-btn').click(function() {
                $(this).toggleClass("menu-link-active");

            });
        });
        //End

         // Product price filter input in min and max Start
        const rangeInput = document.querySelectorAll(".range-input input"),
            priceInput = document.querySelectorAll(".price-input input"),
            range = document.querySelector(".slider .progress");
        let priceGap = 1000;

        priceInput.forEach((input) => {
            input.addEventListener("input", (e) => {
                let minPrice = parseInt(priceInput[0].value),
                    maxPrice = parseInt(priceInput[1].value);

                if (maxPrice - minPrice >= priceGap && maxPrice <= rangeInput[1].max) {
                    if (e.target.className === "input-min") {
                        rangeInput[0].value = minPrice;
                        range.style.left = (minPrice / rangeInput[0].max) * 100 + "%";
                    } else {
                        rangeInput[1].value = maxPrice;
                        range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
                    }
                }
            });
        });

        rangeInput.forEach((input) => {
            input.addEventListener("input", (e) => {
                let minVal = parseInt(rangeInput[0].value),
                    maxVal = parseInt(rangeInput[1].value);

                if (maxVal - minVal < priceGap) {
                    if (e.target.className === "range-min") {
                        rangeInput[0].value = maxVal - priceGap;
                    } else {
                        rangeInput[1].value = minVal + priceGap;
                    }
                } else {
                    priceInput[0].value = minVal;
                    priceInput[1].value = maxVal;
                    range.style.left = (minVal / rangeInput[0].max) * 100 + "%";
                    range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
                }
            });
        });
        //End

         //Grid view system on product in mobile responsive
        $(document).ready(function() {
            $('.grid-btn-mobile').on('click', function() {
                var columns = $(this).data('columns');
                var category = $(this).data('category');
                // console.log(columns);
                $('.product-column[data-category="' + category + '"]')
                    .removeClass('col-md-2 col-md-3 col-md-4 col-md-5 col-md-6 col-sm-12 col-sm-6')
                    .addClass('col-sm-' + columns);

                $('.grid-btn-mobile[data-category="' + category + '"]').removeClass('active');
                $(this).addClass('active');
                // Apply fixed dimensions to images
                $('.product-box[data-category="' + category + '"]')
                    .removeClass(
                        'product-box-col-2 product-box-col-3 product-box-col-4 product-box-col-6 product-box-col-sm-12 product-box-col-sm-6'
                    )
                    .addClass('product-box-col-sm-' + columns);

                $('.product-image2[data-category="' + category + '"]')
                    .removeClass(
                        'product-image2-col-2 product-image2-col-3 product-image2-col-4 product-image2-col-6 product-image2-col-sm-12 product-image2-col-sm-6'
                    )
                    .addClass('product-image2-col-sm-' + columns);
            });
        });
        // End

        //When scroll on window
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        //End

         //category filter category show and hide
        $(document).ready(function() {
            $('.category-header').on('click', function() {
                var toggleId = $(this).find('.toggle-icon').data('toggle');
                $('#' + toggleId).slideToggle();
                var icon = $(this).find('.toggle-icon');
                icon.text(icon.text() === '+' ? '-' : '+');
            });

            $('.sub-category-header').on('click', function() {
                var toggleId = $(this).find('.toggle-icon').data('toggle');
                if (toggleId) {
                    $('#' + toggleId).slideToggle();
                    var icon = $(this).find('.toggle-icon');
                    icon.text(icon.text() === '+' ? '-' : '+');
                }
            });
        });
        //End
