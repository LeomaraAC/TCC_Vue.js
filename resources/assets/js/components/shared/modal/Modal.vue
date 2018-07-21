<template>
    <div class="modal fade" :id="idmodal"  tabindex="-1" role="dialog" :aria-labelledby="iddialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" :id="iddialog">{{titulo}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <slot name="modal-body"></slot>
            </div>
            <div class="modal-footer">
                <slot name="modal-footer"></slot>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['idmodal', 'iddialog', 'titulo']
    }
</script>
