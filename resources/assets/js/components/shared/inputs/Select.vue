<template>
    <div :class="{ 'mb-3':true,'invalid': isInvalid && required || erro }">
            <multiselect  
                v-model="value"
                :id="id"
                :options="options" 
                :show-labels="showl"
                :placeholder="placeholder"
                :deselect-label="deselectLabel"
                :track-by="trackBy"
                :selected-label="selectedLabel"
                :label="label"
                :select-label="selectLabel"
                @close="onTouch">
                <span slot="noResult">Oops! Nenhum elemento encontrado.</span>
        </multiselect>
        <div class="typo__label form__label ml-1">
            <label  v-show="isInvalid">O campo {{campo}} é obrigatório.</label>
            <label v-show="erro && !isInvalid">{{messageErro}}</label>
        </div>
        <input type="hidden" v-model="idSelect" :name="id">
    </div>
</template>
<script>
export default {
    props: {
        campo: {
            type: String,
            default: ''
        },
        erro: {
            type: Boolean,
            default: false
        },
        messageErro: {
            type: String,
            default: ''
        },
        deselectLabel:{
            type: String,
            default: 'Remover'
        },
        id: {
            type: String,
            required: true
        },
        label: {
            type: String,
            default: ''
        },
        options: {
            type: Array,
            required: true
        },
        placeholder: {
            type: String,
            required: true
        },
        required: {
            type: Boolean,
            default: false
        },
        selectLabel: {
            type: String,
            default: 'Selecionar'
        },
        showl: {
            type: Boolean,
            default: true
        },
        trackBy: {
            type: String,
            default: ''
        },
        selected: {
            type: [String, Number] ,
            default: ''
        },
        selectedLabel: {
            type: String,
            default: 'Selecionado'
        }
    },
    data: function () {
        return {
             isTouched: false,
             value: [],
             idSelect: ''
        }
    },
    mounted: function () {
        if(this.selected != '') 
            this.setSelect()
    },
    computed: {
        isInvalid () {
            return this.isTouched && this.required && this.value.length === 0 ;
        }
    },
    methods: {
        onTouch: function () {
            this.isTouched = true;
        },
        reset: function() {
            this.isTouched = false;
            this.value = [];
        },
        valid() {
            return this.value.length !== 0;
        },
        setSelect() {
            this.options.map(e =>{
                if(Object.values(e)[0] == this.selected)
                    this.value = e;
            })
        }
    },
    watch: {
        value() {
            if(this.value == null)
                this.value = [];
            this.idSelect = Object.values(this.value)[0];
            this.$emit('selected', this.value);
        },
        selected() {
            if(this.selected != '')
                this.setSelect();
        }
    }
}
</script>
<style>
    .invalid .typo__label {
        color:crimson;
    }
    .invalid .multiselect__tags {
        border-color: crimson !important;
    }
    .multiselect__tags {
        font-size: 0.9rem !important;
        line-height: 1.6 !important;
        color: #495057 !important;
        border-color: #ced4da !important;
        border-radius: 0.25rem !important;
        /* padding: 0.375rem 40px 0.375rem 0.75rem !important; */
    }
    .multiselect,
    .multiselect__tags,
    .multiselect__select {
        max-height: 100px !important;
        min-height: 33px !important;
    }
    .multiselect, 
    .multiselect__input,
    .multiselect__single {
        font-size: 0.9rem !important;
    }
</style>


