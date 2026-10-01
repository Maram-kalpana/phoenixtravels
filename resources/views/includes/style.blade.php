
<style>
root {
  --primary-color: {{  getColorCode(); }};
}

.top-header {
    background-color: var(--primary-color) !important;
}

.sidebar-wrapper .sidebar-header  .logo-text {
    font-size: 22px;
    margin-left: 10px;
    margin-bottom: 0;
    letter-spacing: 1px;
    color: var(--primary-color) !important;
}

a {
    color: var(--primary-color) !important;
    text-decoration: none;
}
.sidebar-wrapper .sidebar-header  .logo-text {
    font-size: 22px;
    margin-left: 10px;
    margin-bottom: 0;
    letter-spacing: 1px;
    color: var(--primary-color) !important;
}

.sidebar-wrapper .metismenu .mm-active>a,
.sidebar-wrapper .metismenu a:active,
.sidebar-wrapper .metismenu a:focus,
.sidebar-wrapper .metismenu a:hover {
	color: var(--primary-color) !important;
    text-decoration: none;
    background-color: rgb(146 62 185 / 10%);
}

.back-to-top {
    display: none;
    width: 40px;
    height: 40px;
    line-height: 40px;
    text-align: center;
    font-size: 22px;
    color: #fff;
    position: fixed;
    border-radius: 50%;
    bottom: 20px;
    right: 12px;
    background-color: var(--primary-color) !important;
    z-index: 5;
}

.btn-mobile-filter {
    width: 45px;
    height: 45px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
    color: #ffffff;
    background-color: var(--primary-color) !important;
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
    cursor: pointer;
    position: fixed;
    left: 0;
    top: 55%;
    z-index: 3;
}
.steps-light .step-item.active .step-count, .steps-light .step-item.active .step-progress {
	color: #ffffff;
    background-color: var(--primary-color) !important;
}

.border-primary{
			border-color: var(--primary-color) !important;
		}
		.btn-primary {
			color: #fff;
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
		}

		.btn-primary:active{
			color: #fff !important;
           background-color: var(--primary-color) !important;
           border-color: var(--primary-color) !important;
           }
		   .text-primary{
			color: var(--primary-color) !important;
		   }
		   .bx-color {
			color: var(--primary-color) !important;
		   }
           .pace .pace-progress {
			background: var(--primary-color) !important;
		   }

           .page-item.active .page-link {
			z-index: 3;
            color: #fff !important;
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
		   }
</style>

