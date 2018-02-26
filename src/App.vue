<template>
    <section
        id="searcher"
        class="container"
    >
        <div class="content">

            <app-list :data="elements"/>
        </div>
    </section>
</template>

<script>
import AppList from './components/List';

export default {
    name: 'app',
    data() {
        return {
            elements: [
                {
                    type: 'page',
                    image: {
                        src: 'https://www.agpd.es/portalwebAGPD/images/prensa/2018/01_ene_18/portada_guia_compra_segura-.jpg',
                        alt: 'Guía para comprar con seguridad en internet',
                        longdesc: 'Guía para comprar con seguridad en internet'
                    },
                    title: 'Guia compra digital web',
                    body: '[...] de compra o contratación online, y ofrece consejos y recomendaciones desde diversos enfoques: la privacidad (Agencia Española de Protección de Datos), la seguridad (INCIBE), el consumo (AECOSAN) y la persecución de las[...]',
                    group: 'DOCUMENTACIONES / Publicaciones'
                },
                {
                    type: 'page',
                    image: {
                        src: 'https://www.agpd.es/portalwebAGPD/images/prensa/2018/01_ene_18/portada_guia_compra_segura-.jpg',
                        alt: 'Guía para comprar con seguridad en internet',
                        longdesc: 'Guía para comprar con seguridad en internet'
                    },
                    title: 'Guia compra digital web',
                    body: '[...] de compra o contratación online, y ofrece consejos y recomendaciones desde diversos enfoques: la privacidad (Agencia Española de Protección de Datos), la seguridad (INCIBE), el consumo (AECOSAN) y la persecución de las[...]',
                    group: 'DOCUMENTACIONES / Publicaciones'
                },
                {
                    type: 'page',
                    image: {
                        src: 'https://www.agpd.es/portalwebAGPD/images/prensa/2018/01_ene_18/portada_guia_compra_segura-.jpg',
                        alt: 'Guía para comprar con seguridad en internet',
                        longdesc: 'Guía para comprar con seguridad en internet'
                    },
                    title: 'Guia compra digital web',
                    body: '[...] de compra o contratación online, y ofrece consejos y recomendaciones desde diversos enfoques: la privacidad (Agencia Española de Protección de Datos), la seguridad (INCIBE), el consumo (AECOSAN) y la persecución de las[...]',
                    group: 'DOCUMENTACIONES / Publicaciones'
                },
                {
                    type: 'page',
                    image: {
                        src: 'https://www.agpd.es/portalwebAGPD/images/prensa/2018/01_ene_18/portada_guia_compra_segura-.jpg',
                        alt: 'Guía para comprar con seguridad en internet',
                        longdesc: 'Guía para comprar con seguridad en internet'
                    },
                    title: 'Guia compra digital web',
                    body: '[...] de compra o contratación online, y ofrece consejos y recomendaciones desde diversos enfoques: la privacidad (Agencia Española de Protección de Datos), la seguridad (INCIBE), el consumo (AECOSAN) y la persecución de las[...]',
                    group: 'DOCUMENTACIONES / Publicaciones'
                },
                {
                    type: 'page',
                    image: {
                        src: 'https://www.agpd.es/portalwebAGPD/images/prensa/2018/01_ene_18/portada_guia_compra_segura-.jpg',
                        alt: 'Guía para comprar con seguridad en internet',
                        longdesc: 'Guía para comprar con seguridad en internet'
                    },
                    title: 'Guia compra digital web',
                    body: '[...] de compra o contratación online, y ofrece consejos y recomendaciones desde diversos enfoques: la privacidad (Agencia Española de Protección de Datos), la seguridad (INCIBE), el consumo (AECOSAN) y la persecución de las[...]',
                    group: 'DOCUMENTACIONES / Publicaciones'
                },
                {
                    type: 'page',
                    image: {
                        src: 'https://www.agpd.es/portalwebAGPD/images/prensa/2018/01_ene_18/portada_guia_compra_segura-.jpg',
                        alt: 'Guía para comprar con seguridad en internet',
                        longdesc: 'Guía para comprar con seguridad en internet'
                    },
                    title: 'Guia compra digital web',
                    body: '[...] de compra o contratación online, y ofrece consejos y recomendaciones desde diversos enfoques: la privacidad (Agencia Española de Protección de Datos), la seguridad (INCIBE), el consumo (AECOSAN) y la persecución de las[...]',
                    group: 'DOCUMENTACIONES / Publicaciones'
                }
            ]
        };
    },
    components: {
        AppList
    }
};
</script>

<style lang="scss">
    @import './scss/main';
</style>
