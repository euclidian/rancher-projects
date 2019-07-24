<template>
  <v-app>
    <div class="text-xs-center">
      <v-dialog
        v-model="dialog"
        width="500"
      >
        <v-card>
          <v-card-title
            class="headline grey lighten-2"
            primary-title
          >
            Add Stack To Database
          </v-card-title>

          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12>
                  <v-textarea
                    v-model="remark"
                    name="input-7-1"
                    label="Remark"
                    hint="Hint text"
                  ></v-textarea>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
            color="primary"
            flat
            @click="save"
            >Simpan</v-btn>
            <v-btn
              color="primary"
              flat
              @click="dialog = false"
            >
              Cancel
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
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
          <td>
            <v-tooltip top>
                <template v-slot:activator="{ on }">
                <v-btn v-if="props.item.status == null" fab dark small color="primary"  @click="toDB(props.item.id)" v-on="on">
                  <v-icon dark>save_alt</v-icon>
                </v-btn>
              </template>
              <span>Add To Database</span>
              </v-tooltip>
          </td>
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
      baseURL: '/tiketux/rancher/stack/api/'
    });
    this.list();
  },
  data() {
    return {
      dialog:false,
      stackdb:[],
      rowsPerPageItems: [25, 50, 75, 100],
      pagination: {
        rowsPerPage: 25
      },
      remark:null,
      rancherprojects:[],
      header:[
        { text: "ID Rancher ", value: "id" },
        { text: "Nama Rancher", value: "name" },
        { text: "Deskripsi Rancher", value: "description" },
        { text: "ID Akun", value: "accountId" },
        { text: "State", value: "state" },
        { text: "Action", value: "id" }
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
          that.rancherprojects.forEach(function(item, index) {
            that.$set(that.rancherprojects[index], "status", null);
            that.detail(index, item);
          });
          console.log(response.data);
        })
        .catch(error => {
          console.log(response.data);                        
        });              
    },
    toDB: function(params){
    var that = this;
      that.dialog=true;
      that.id_rancher = params;
    },
    detail: function(index, item){
      var that = this;
      that.instance
      .post('cekstackdb',{
        "id_stack" : item.id
      })
      .then(response => {
        that.rancherprojects[index].status = response.data.data;
        console.log(response.data);
        })
        .catch(error => {
          console.log(response.data);                        
        }); 
    },
    save: function(){
    var that = this;
    that.instance
      .post('addstackdb',{
        "stack_id" : that.id_rancher,
        "remark" : that.remark
      })
        .then(response => {
          console.log(response);
          that.dialog = false;
          that.list();
        })
        .catch(error => {
          console.log(response.data);                        
        }); 
    }
  }
};
</script>
