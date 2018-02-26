<template>
    <div>
        <div class="header">
            <a
                href="'/files/'+value['filename_s']"
                target="_blank"
            >
                value['filename_s']
            </a>
        </div>
        <ul class="body">
            <li>
                <b>Fecha:</b>
                (new Date(value['fechafirma_rdt'][0])).toLocaleString()
            </li>
            <li>
                <b>Tipo Documento:</b> value['tipodocumento_s']
            </li>
            <li>
                <b>Ley de Gravedad:</b> value['leygravedad_s']
            </li>
            <li>
                <b>Artículo Infringido:</b>
                value['articuloinfringido_s']
            </li>
            <li if="value['hl']">
                <div class="active-facet" />
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'app-list-docs'
}
</script>