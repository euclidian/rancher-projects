<template>
  <v-app>
    <v-card>
      <v-card-title primary-title>
        <div>
          <h3 class="headline mb-0">Daftar Rancher Project</h3>
        </div>
      </v-card-title>
      <v-data-table
        :rows-per-page-items="rowsPerPageItems"
        :pagination.sync="pagination"
        :headers="header"
        :items="rancherprojects"
        class="elevation-1"
      >
        <template v-slot:items="props">
          <td>{{ props.item.id }}</td>
          <td>{{ props.item.name }}</td>
          <td>{{ props.item.description }}</td>
          <td>{{ props.item.accountId }}</td>
          <td>{{ props.item.state }}</td>
        </template>
      </v-data-table>
    </v-card>
  </v-app>
</template>

<script>
export default {
  name: "RancherProjectsComponent",
  mounted() {
    this.instance = axios.create({
      baseURL: '/tiketux/rancherprojects/api/'
    });
    this.list();
  },
  data() {
    return {
      rowsPerPageItems: [25, 50, 75, 100],
      pagination: {
        rowsPerPage: 25
      },
      rancherprojects:[],
      header:[
        { text: "ID Rancher ", value: "id" },
        { text: "Nama Rancher", value: "name" },
        { text: "Deskripsi Rancher", value: "description" },
        { text: "ID Akun", value: "accountId" },
        { text: "State", value: "state" }
      ]
    };
  },
  methods: {
     list: function(){
      var that = this; 
      that.instance
        .get('liststack')
        .then(response => {
          that.rancherprojects = response.data;
          console.log(response.data);
        })
        .catch(error => {
          console.log(response.data);                        
        });              
    }
  }
};
</script>
