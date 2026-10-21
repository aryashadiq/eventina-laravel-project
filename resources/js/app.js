import './bootstrap';
// Flowbite (installed via npm) — dynamically import and guard against double-loading
if (typeof window !== 'undefined') {
    if (!window._flowbiteLoaded) {
        window._flowbiteLoaded = true;
        import('flowbite').catch(err => console.error('Flowbite load error', err));
    }
}
