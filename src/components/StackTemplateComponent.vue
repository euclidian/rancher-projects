<template>
  <div>
    <v-btn @click="addedit = true" fixed dark fab bottom right color="blue">
      <v-icon>add</v-icon>
    </v-btn>
    <v-dialog v-model="addedit" fullscreen hide-overlay transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click="closeAddTemplate">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>Stack Template</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat @click="save">Save</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs4>
                <v-text-field
                  v-model="name"
                  :error-messages="errortxt!=null?errortxt.name:''"
                  label="Template Name"
                ></v-text-field>
              </v-flex>
            </v-layout>
            <v-layout wrap>
              <v-flex xs6>
                <v-textarea
                  v-model="docker_compose"
                  auto-grow
                  outline
                  :error-messages="errortxt!=null?errortxt.docker_compose_yml:''"
                  label="Docker Compose Config"
                ></v-textarea>
              </v-flex>
              <v-flex xs6>
                <v-textarea
                  v-model="rancher_compose"
                  auto-grow
                  outline
                  :error-messages="errortxt!=null?errortxt.rancher_compose_yml:''"
                  label="Rancher Compose Config"
                ></v-textarea>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-toolbar flat color="white">
      <v-toolbar-title>List Stack Template</v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-data-table :headers="headers" :loading="loading" :items="templates" class="elevation-1">
      <template v-slot:items="props">
        <td>{{ props.item.name }}</td>
        <td>
          <v-btn @click="deleteTemplate(props.item.id)" outline small fab color="error">
            <v-icon>delete</v-icon>
          </v-btn>
          <v-btn @click="detail(props.item.id)" outline small fab color="success">
            <v-icon>edit</v-icon>
          </v-btn>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
export default {
  mounted() {
    this.list();
  },
  data() {
    return {
      loading: false,
      addedit: false,
      name: null,
      docker_compose: null,
      rancher_compose: null,
      template_id: null,
      templates: [],
      errortxt: null,
      headers: [
        {
          text: "Template Name",
          value: "name"
        },
        { text: "Actions", value: "name", sortable: false }
      ]
    };
  },
  methods: {
    closeAddTemplate() {
      this.addedit = false;
      this.name = null;
      this.docker_compose = null;
      this.rancher_compose = null;
      this.template_id = null;
      this.errortxt = null;
    },
    save() {
      var that = this;
      that.loading = true;
      axios
        .post("/tiketux/rancherprojects/api/template/save", {
          template_id: that.template_id,
          name: that.name,
          docker_compose_yml: that.docker_compose,
          rancher_compose_yml: that.rancher_compose
        })
        .then(response => {
          console.log(response.data.data);
          that.errortxt = null;
          that.closeAddTemplate();
          that.loading = false;
          that.list();
        })
        .catch(error => {
          console.log(error.response.data);
          that.errortxt = error.response.data.errors;
        });
    },
    list() {
      var that = this;
      that.loading = true;
      axios
        .get("/tiketux/rancherprojects/api/template/list")
        .then(response => {
          console.log(response.data.data);
          that.templates = response.data.data;
          that.loading = false;
        })
        .catch(error => {
          console.log(error.response.data);
        });
    },
    detail(id) {
      var that = this;
      that.loading = true;
      axios
        .get("/tiketux/rancherprojects/api/template/detail/" + id)
        .then(response => {
          console.log(response.data.data);
          that.addedit = true;
          that.template_id = response.data.data.id;
          that.name = response.data.data.name;
          that.docker_compose = response.data.data.docker_compose_yml;
          that.rancher_compose = response.data.data.rancher_compose_yml;
          that.loading = false;
        })
        .catch(error => {
          console.log(error.response.data);
        });
    },
    deleteTemplate(id) {
      var that = this;
      that.loading = true;
      axios
        .get("/tiketux/rancherprojects/api/template/delete/" + id)
        .then(response => {
          console.log(response.data.data);
          that.loading = false;
          that.list();
        })
        .catch(error => {
          console.log(error.response.data);
        });
    }
  }
};
</script>
