<template>
  <div>
    <v-btn @click="save" fixed dark fab bottom right color="blue">
      <v-icon>save</v-icon>
    </v-btn>
    <v-layout wrap>
      <v-flex xs3>
        <v-select
          @change="listuser"
          v-model="service"
          item-value="id"
          item-text="name"
          :items="services"
          label="Service"
        ></v-select>
      </v-flex>
    </v-layout>
    <v-toolbar flat color="white">
      <v-toolbar-title>Service Assignment</v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      v-model="selected"
      select-all
      :items="users"
      :loading="loading"
      class="elevation-1"
    >
      <template v-slot:items="props">
        <tr :active="props.selected" @click="props.selected = !props.selected">
          <td>
            <v-checkbox :input-value="props.selected" primary hide-details></v-checkbox>
          </td>
          <td>{{ props.item.name }}</td>
          <td>{{ props.item.email }}</td>
        </tr>
      </template>
    </v-data-table>
  </div>
</template>

<script>
export default {
  mounted() {
    this.listservice();
  },
  data() {
    return {
      loading: false,
      name: null,
      assignments: {
        rancher: [],
        docker: []
      },
      users: [],
      services: [],
      service: null,
      errortxt: null,
      headers: [
        {
          text: "Name",
          value: "name"
        },
        {
          text: "Email",
          value: "email"
        }
      ],
      selected: []
    };
  },
  methods: {
    save() {
      var that = this;
      that.loading = true;
      axios
        .post("/tiketux/rancherprojects/api/assignment/save", {
          project_id: that.service,
          users: that.selected
        })
        .then(response => {
          console.log(response.data.data);
          that.addassignment = false;
          that.name = null;
          that.loading = false;
          that.errortxt = null;
          that.list();
        })
        .catch(error => {
          console.log(error.response.data);
          that.errortxt = error.response.data.errors;
          that.loading = false;
        });
    },
    list() {
      var that = this;
      that.loading = true;
      axios
        .post("/tiketux/rancherprojects/api/assignment/list", {
          project_id: that.service
        })
        .then(response => {
          console.log(response.data.data);
          that.selected = [];
          response.data.data.forEach(element => {
            that.users.forEach(element2 => {
              if (element.user_id == element2.id) {
                that.selected.push(element2);
              }
            });
          });
          that.loading = false;
        })
        .catch(error => {
          console.log(error.response.data);
        });
    },
    listservice() {
      var that = this;
      that.loading = true;
      axios
        .get("/tiketux/rancherprojects/api/assignment/list_services")
        .then(response => {
          console.log(response.data.data);
          that.services = response.data.data;
          that.service = response.data.data[0].id;
          that.listuser();
          that.loading = false;
        })
        .catch(error => {
          console.log(error.response.data);
        });
    },
    listuser() {
      var that = this;
      axios
        .get("/tiketux/usermanagement/api/list")
        .then(response => {
          that.users = response.data.data;
          that.list();
          console.log(response.data);
        })
        .catch(error => {
          console.log(response.data);
        });
    }
  }
};
</script>
