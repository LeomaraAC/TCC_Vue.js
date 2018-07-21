<template>
<div class="text-left">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-if="select" class="opcoes"></th>
                <th scope="col" v-if="apagar" class="opcoes"></th>
                <th scope="col" v-if="editar" class="opcoes"></th>
                <th scope="col" v-for="item in header"  :key="item.id" >
                    <b @click="sort(item.id)" :class="{'cursor':issort}">
                        {{item.label}}
                        <i v-if="issort" :class="{'fas fa-sort-amount-down' : (item.id == sortProperty && sortDirection == 'desc')}"></i>
                        <i v-if="issort" :class="{'fas fa-sort-amount-up':  (item.id == sortProperty && sortDirection == 'asc')}"></i>
                    </b>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in conteudotabela" :key="Object.values(item)[0]">
                <td v-if="select">
                    <sara-checkbox @checked="checked" :name="Object.values(item)[0]" 
                                    :ischecked="estamarcado(item)" :item="item" :update="update"></sara-checkbox>
                </td>
                <td v-if="apagar" class="cursor" @click="remove(item)">
                    <i class="fas fa-trash-alt"></i>
                </td>
                <td v-if="editar" class="cursor" >
                    <i class="fas fa-edit"></i>
                </td>
                <td v-for="(i, key) in item" :key="i" v-if="listaExceto.indexOf(key) == -1">{{i}}</td>
            </tr>
        </tbody>
    </table>
</div>
</template>

<script>
    export default {
        props:['conteudotabela', 'header', 'filtroinicial', 'select', 'exceto', 'issort','apagar', 'editar'],
        data: function () {
            return {
                itens: this.conteudotabela,
                sortProperty: this.filtroinicial,
                sortDirection: 'asc',
                listaSelect: [],
                listaExceto: this.exceto || [],
                update: []
            }
        },
        methods: {
            sort (property) {
                this.sortProperty = property;
                this.sortDirection = this.sortDirection == 'asc' ? 'desc' : 'asc';
                this.$emit("sort", [this.sortProperty, this.sortDirection]);
            },
            checked(isChecked) {                
                if(isChecked[0]){
                    this.listaSelect.push(isChecked[1]);
                }
                else {
                   this.remove(isChecked[1]);
                } 
                this.ordenaLista();   
                this.$store.commit('settItens', this.listaSelect);
            },
            ordenaLista() {
                this.listaSelect.sort((item1, item2) => (item1.nomeTela <  item2.nomeTela) ? -1 : 1); 
            },
            returnIndex (item) {
                let index = -1;
                if(item instanceof Object  ){
                     let cont = 0;
                     for (let [key, value] of Object.entries(this.listaSelect)){
                        if((Object.values(item)[0] == Object.values(value)[0])){   
                            index = cont;                            
                        }
                        cont++;
                    }
                }
                else
                    index = this.listaSelect.indexOf(item);
                return index;
            },
            remove (isChecked) {
                 if(this.listaSelect.length == 0) {
                    for (let [key, value] of Object.entries( this.$store.state.itens)) {
                        this.checked([true, value]);
                    }
                }
                let  index = this.returnIndex(isChecked);
                if(index > -1){
                    this.update = isChecked;
                    this.listaSelect.splice(index, 1);
                    this.$store.commit('settItens', this.listaSelect);
                }
                else
                    this.$emit('deleteback',isChecked);
            },
            estamarcado(item) {
                return this.returnIndex(item) > -1
            }
        }
    }
</script>
<style scoped>
    .opcoes {
        width:5.5%;
        table-layout: auto
    }
    b {
        color: #17a2b8;
    }
    b i{
        font-size: 18px
    }
    .cursor:hover {
        cursor: pointer;
    }
    td i {
        font-size: 18px;
    }
    td:hover i {
        color: #179910;
    }
</style>

