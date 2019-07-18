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
                    value="Add Remark"
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
            v-if="remark!=null"
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
          <td v-on="detail(props.item.id)">{{id_test}}
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
      baseURL: '/tiketux/rancherprojects/api/'
    });
    this.list();
    this.listStackDB();
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
          console.log(response.data);
          
        })
        .catch(error => {
          console.log(response.data);                        
        }); 
      that.instance
        .get('liststackdb')
        .then(response => {
          that.stackdb = response.data.data;
          console.log(response.data.data);
        })
        .catch(error => {
          console.log(response.data);                        
        });             
    },
    listStackDB: function(){
    var that = this; 
      
    },
    toDB: function(params){
    var that = this; 
      that.id_rancher = params;
      that.dialog=true;
    },
    save: function(){
    var that = this;
    that.instance
      .post('addstackdb',{
        "stack_id" : that.id_rancher,
        "remark" : that.remark
      })
        .then(response => {
          that.list();
          console.log(response);
        })
        .catch(error => {
          console.log(response.data);                        
        }); 
    },
    detail: function(params){
      var that = this;
      that.instance
      .post('cekstackdb',{
        "id_stack" : params
      })
        .then(response => {
          that.data1 = response.data.data;
          if(that.data1.lenght == 0){
          that.id_test=that.data1;
          }else{
          that.id_test=that.data1;
          }
          //console.log(response.data.data);
        })
        .catch(error => {
          console.log(response.data);                        
        }); 
    }
  }
};
</script>
