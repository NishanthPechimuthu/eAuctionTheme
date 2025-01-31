<style>
    /* Custom Carousel Styles */
    .carousel-image-wrapper {
        position: relative;
        overflow: hidden;
        max-height: 90vh;
    }

    .carousel-image-wrapper::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 40%;
        background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.7) 100%);
    }

    .carousel-caption {
        bottom: auto;
        padding: 2rem;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 15px;
        backdrop-filter: blur(5px);
    }

    .carousel-indicators [data-bs-target] {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin: 0 8px;
        border: 2px solid #fff;
        background-color: transparent;
    }

    .carousel-indicators .active {
        background-color: #fff;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
    }

    /* Responsive Text Sizing */
    .carousel-caption h5 {
        font-size: 2.5rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }

    .carousel-caption p {
        font-size: 1.2rem;
        line-height: 1.5;
    }

    /* Mobile Optimization */
    @media (max-width: 768px) {
        .carousel-image-wrapper {
            max-height: 60vh;
        }

        .carousel-caption {
            padding: 1rem;
            width: 90%;
        }

        .carousel-caption h5 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .carousel-caption p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .btn-warning {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
    }

    /* Hover Effects */
    .btn-warning {
        transition: all 0.3s ease;
        border: none;
        padding: 0.75rem 1.5rem;
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
</style>