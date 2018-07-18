<template>
<div class="theme--light" v-if="headers">
    <v-data-table
      :headers="headers"
      :items="desserts"
      hide-actions
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <td>{{ props.item.name }}</td>
        <td class="text-xs-right">{{ props.item.calories }}</td>
        <td class="text-xs-right">{{ props.item.fat }}</td>
        <td class="text-xs-right">{{ props.item.carbs }}</td>
        <td class="text-xs-right">{{ props.item.protein }}</td>
        <td class="text-xs-right">{{ props.item.iron }}</td>
      </template>
      <template slot="no-data">
        <v-alert :value="true" color="red" icon="warning">
          Nenhum dado encontrado :(
        </v-alert>
      </template>
    </v-data-table>
</div>
</template>

<script>
export default {
  props: ['headers', 'desserts'],
  data () {
    return { }
  }
};
</script>