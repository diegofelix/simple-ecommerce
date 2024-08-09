import './bootstrap';
import 'preline';

// When using livewire, we need to reinitialize the scripts after each navigation
// This is because the scripts are not reloaded when using Turbolinks
// @see https://livewire.laravel.com/docs/navigate#dont-rely-on-domcontentloaded
document.addEventListener('livewire:navigated', () => {
    // This code below reinitialize the scripts from preline package
    // (The hamburger menu and the dropdowns)
    window.HSStaticMethods.autoInit();
})
