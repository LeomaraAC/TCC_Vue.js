<template>
    <label class="check ">
        {{label}}
        <input type="checkbox" name="name" @click="checked" checked="checked" v-if="ischecked">
        <input type="checkbox" name="name" @click="checked" v-if="!ischecked">
        <span class="checkmark"></span>
    </label>
</template>
<script>
    export default {
        props: ['label', 'name', 'ischecked', 'item'],
        data() {
            return{
                check: this.ischecked
            }
        },
        methods: {
            checked ( ) {
                this.check = !this.check;
                if(this.item == undefined)
                    this.$emit('checked', [this.check, this.name]);
                else
                    this.$emit('checked', [this.check, this.item])
            }
        }
    }
</script>
<style scoped>
.check {
    display: block;
    position: relative;
    padding-left: 25px;
    margin-bottom: 12px;
    padding-right: 15px;
    cursor: pointer;
    font-size: 13px !important;
    user-select: none;
}

/* Hide the browser's default checkbox */
.check input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

/* Create a custom checkbox */
.checkmark {
    position: absolute;
    top: 3px;
    left: 0;
    height: 18px;
    width: 18px;
    background-color: #fff ;
    border-color:#179910;
    border-style:solid;
    border-width:2px;
}



/* When the checkbox is checked, add a blue background */
.check input:checked ~ .checkmark {
    background-color: #fff  ;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the checkmark when checked */
.check input:checked ~ .checkmark:after {
    display: block;
}

/* Style the checkmark/indicator */
.check .checkmark:after {
    left: 5px;
    top: 1px;
    width: 5px;
    height: 10px;
    border: solid ;
    border-color:#179910;
    border-width: 0 3px 3px 0;
    transform: rotate(45deg);
}
</style>

