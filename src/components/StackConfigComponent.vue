<template>
  <div>
    <v-btn @click="showAddConfig" fixed dark fab bottom right color="blue">
      <v-icon>add</v-icon>
    </v-btn>
    <v-dialog v-model="detailconfig" persistent max-width="800px">
      <v-card>
        <v-card-title>
          <span class="headline">Stack Config</span>
        </v-card-title>

        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12>
                <v-text-field v-model="name" label="Template Name"></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-textarea v-model="docker_compose" label="Docker Compose Config"></v-textarea>
              </v-flex>
              <v-flex xs12>
                <v-textarea v-model="rancher_compose" label="Rancher Compose Config"></v-textarea>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error" flat @click="detailconfig = false">Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="addconfig" persistent max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Stack Config</span>
        </v-card-title>

        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12>
                <v-select
                  @change="selectTemplateChanged"
                  v-model="template_id"
                  item-value="id"
                  item-text="name"
                  :items="templates"
                  label="Template"
                ></v-select>
              </v-flex>
              <v-flex xs12>
                <v-text-field
                  v-model="name"
                  :error-messages="errortxt!=null?errortxt.name:''"
                  label="Config Name"
                ></v-text-field>
              </v-flex>
              <v-subheader v-if="configs.docker.length">Docker Config</v-subheader>
              <v-flex
                xs12
                v-for="items in configs.docker"
                v-bind:key="items.key"
              >
                <v-text-field
                  v-model="items.value"
                  :error-messages="errortxt!=null?errortxt['configs.docker.'+index+'.value']:''"
                  :label="items.key"
                ></v-text-field>
              </v-flex>
              <v-subheader v-if="configs.rancher.length">Rancher Config</v-subheader>
              <v-flex
                xs12
                v-for="items in configs.rancher"
                v-bind:key="items.key"
              >
                <v-text-field
                  v-model="items.value"
                  :error-messages="errortxt!=null?errortxt.configs.rancher.index.value:''"
                  :label="items.key"
                ></v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error" flat @click="addconfig = false">Cancel</v-btn>
          <v-btn color="blue darken-1" flat @click="save">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-toolbar flat color="white">
      <v-toolbar-title>List Stack Config</v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-data-table :headers="headers" :loading="loading" :items="data_configs" class="elevation-1">
      <template v-slot:items="props">
        <td>{{ props.item.name }}</td>
        <td>
          <v-btn @click="deleteConfig(props.item.id)" outline small fab color="error">
            <v-icon>delete</v-icon>
          </v-btn>
          <v-btn @click="detail(props.item.id)" outline small fab color="blue">
            <v-icon>info</v-icon>
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
      addconfig: false,
      name: null,
      configs: {
        rancher: [],
        docker: []
      },
      detailconfig: false,
      docker_compose: null,
      rancher_compose: null,
      data_configs: [],
      template_id: null,
      templates: [],
      errortxt: null,
      headers: [
        {
          text: "Config Name",
          value: "name"
        },
        { text: "Actions", value: "name", sortable: false }
      ]
    };
  },
  methods: {
    showAddConfig() {
      var that = this;
      that.loading = true;
      axios
        .get("/tiketux/rancherprojects/api/template/list")
        .then(response => {
          console.log(response.data.data);
          that.templates = response.data.data;
          that.template_id = response.data.data[0].id;
          that.selectTemplateChanged();
          that.addconfig = true;
          that.loading = false;
        })
        .catch(error => {
          console.log(error.response.data);
        });
    },
    selectTemplateChanged() {
      var that = this;
      that.loading = true;
      axios
        .get(
          "/tiketux/rancherprojects/api/config/detail_template/" +
            that.template_id
        )
        .then(response => {
          console.log(response.data.data);
          that.configs = response.data.data;
          that.loading = false;
        })
        .catch(error => {
          console.log(error.response.data);
          that.errortxt = error.response.data.errors;
        });
    },
    save() {
      var that = this;
      that.loading = true;
      axios
        .post("/tiketux/rancherprojects/api/config/save", {
          template_id: that.template_id,
          name: that.name,
          configs: that.configs
        })
        .then(response => {
          console.log(response.data.data);
          that.addconfig = false;
          that.name = null;
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
        .get("/tiketux/rancherprojects/api/config/list")
        .then(response => {
          console.log(response.data.data);
          that.data_configs = response.data.data;
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
        .get("/tiketux/rancherprojects/api/config/detail/" + id)
        .then(response => {
          console.log(response.data.data);
          that.detailconfig = true;
          that.template_id = response.data.data.id;
          that.name = response.data.data.name;
          that.docker_compose = response.data.data.generated_docker_compose_yml;
          that.rancher_compose = response.data.data.generated_rancher_compose_yml;
          that.loading = false;
        })
        .catch(error => {
          console.log(error.response.data);
        });
    },
    deleteConfig(id) {
      var that = this;
      that.loading = true;
      axios
        .get("/tiketux/rancherprojects/api/config/delete/" + id)
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
