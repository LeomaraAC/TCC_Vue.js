<template>
    <div class="box" :class="['box-' + color]">
        <div class="box-header" :class="{'with-border' : borderOnHeader}">
            <div class="box-title" v-if="!noTitle">
                <slot name="title">Put your title here using slot with name title</slot>
            </div>
            <div class="box-tools pull-right" v-if="!noTitle">
                <slot name="box-tools">
                    <button v-if="isCollapsable" type="button" class="btn btn-box-tool" @click="collapse"><i class="fa" :class="collapseIcon"></i></button>
                </slot>
            </div>
        </div>
        <div class="box-body" :class="[{'fade-out' : isCollapsed }, {'fade-in' : !isCollapsed }]">
            <slot>Put your content here using default slot</slot>
        </div>
        <div class="box-footer"  :class="[{'fade-out' : isCollapsed }, {'fade-in' : !isCollapsed }]" v-if="hasFooterSlot()">
            <slot name="footer">This is the footer!</slot>
        </div>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        isCollapsed : false,
        isCollapsable: this.collapsable,
        headerHaveBorder: this.borderHeader,
      }
    },
    computed: {
      collapseIcon: function () {
        return this.isCollapsed ? 'fa-plus' : 'fa-minus'
      },
      borderOnHeader: function() {
        if (this.noTitle) return false;
        return this.headerHaveBorder;
      }
    },
    props: {
      collapsable: {
        type: Boolean,
        default: true
      },
      borderHeader: {
        type: Boolean,
        default: true
      },
      color: {
        type: String,
        default: 'default',
        validator: value => {
          let valid = ['','primary','info', 'danger', 'warning', 'success','default']
          return valid.includes(value)
        }
      },
      noTitle: {
        type: Boolean,
        default: false
      }
    },
    methods: {
      hasFooterSlot() {
        return !!this.$slots['footer']
      },
      collapse() {
        this.isCollapsed = !this.isCollapsed
      },
    }
  }
</script>
<style>
.fade-out {
    opacity: 0;
    height: 0;
    padding: 0 !important;
    transition: all 0.15s ease-out;
    overflow: hidden;
}
.fade-in {
    opacity: 1;
    height: 100%;
    padding: 10 !important;
    transition: all 0.15s ease-in;
}

.box {
  position: relative;
  border-radius: 3px;
  background: #ffffff;
  border-top: 3px solid #d2d6de;
  margin-bottom: 20px;
  width: 100%;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
}
.box.box-primary {
  border-top-color: #3c8dbc;
}
.box.box-info {
  border-top-color: #00c0ef;
}
.box.box-danger {
  border-top-color: #dd4b39;
}
.box.box-warning {
  border-top-color: #f39c12;
}
.box.box-success {
  border-top-color: #00a65a;
}
.box.box-default {
  border-top-color: #d2d6de;
}
/* .box.collapsed-box .box-body,
.box.collapsed-box .box-footer {
  display: none;
} */

.box-header {
  color: #444;
  display: block;
  padding: 10px;
  position: relative;
}
.box-header.with-border {
  border-bottom: 1px solid #f4f4f4;
}
.box-header > .fa,
.box-header .box-title {
  display: inline-block;
  font-size: 18px;
  margin: 0;
  line-height: 1;
}
.box-header > .fa {
  margin-right: 5px;
}
.box-header > .box-tools {
  position: absolute;
  right: 10px;
  top: 5px;
}
.btn-box-tool {
  padding: 5px;
  font-size: 12px;
  background: transparent;
  color: #97a0b3;
}
.btn-box-tool:hover {
  color: #179910;
}
.btn-box-tool:focus {
  box-shadow: 0 0 0 0;
  border: 0 none;
  outline: 0;
}
.btn-box-tool.btn:active {
  box-shadow: none;
}
.box-body {
  border-top-left-radius: 0;
  border-top-right-radius: 0;
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
  padding: 10px;
}
.box-footer {
  border-top-left-radius: 0;
  border-top-right-radius: 0;
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
  border-top: 1px solid #f4f4f4;
  padding: 10px;
  background-color: #ffffff;
}
</style>
