import './bootstrap.js';

    /*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import CanvasConfetti from 'canvas-confetti';

document.body.addEventListener('click', (event) => {
    const { clientX, clientY } = event;
    const confetti = new CanvasConfetti();
    confetti({
        particleCount: 100,
        spread: 70,
        origin: { x: clientX / window.innerWidth, y: clientY / window.innerHeight },
    });
});
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
