import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

// Initialisation de la progression Inertia
InertiaProgress.init();

// Création de l'application Inertia
createInertiaApp({
    resolve: name => {
        // Vérifiez si 'name' se termine par '.vue'
        if (name.endsWith('.vue')) {
            // Si 'name' contient déjà l'extension, retirez-la
            name = name.slice(0, -4); // Retire les 4 derniers caractères
        }
        // Importation du composant
        return import(`./Pages/${name}.vue`);
    },
    setup({ el, App, props }) {
        // Création et montage de l'application Vue
        createApp({ render: () => h(App, props) }).mount(el);
    },
});