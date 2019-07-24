<template>
  <v-app>
    <v-card v-if="editStack">
        <v-card-title>
          <span class="headline">Edit Stack</span>
        </v-card-title>
        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12>
                  <v-text-field
                    v-model="rancherStackId"
                    :counter="10"
                    :rules="nameRules"
                    label="Name"
                    required
                    readonly
                  ></v-text-field>
                  <v-textarea
                    v-model="remarkStack"
                    name="input-7-1"
                    label="Remark"
                    hint="Hint text"
                  ></v-textarea>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error" flat @click="editStack = false">Batal</v-btn>
          <v-btn color="primary" flat @click="save">Simpan</v-btn>
        </v-card-actions>
      </v-card>
    <v-card v-else>
      <v-card-title primary-title>
        <div>
          <h3 class="headline mb-0">Daftar Stack</h3>
        </div>
      </v-card-title>
      <v-data-table
        :rows-per-page-items="rowsPerPageItems"
        :pagination.sync="pagination"
        :headers="header"
        :items="stacks"
        class="elevation-1"
      >
        <template v-slot:items="props">
          <td>{{ props.item.rancher_stack_id }}</td>
          <td>{{ props.item.remark }}</td>
          <td>{{ props.item.created_at }}</td>
          <td>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="warning"  @click="formEditStack(props.item.rancher_stack_id)" v-on="on">
                  <v-icon dark>edit</v-icon>
                </v-btn>
              </template>
              <span>Edit Stack in Database</span>
            </v-tooltip>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="error"  @click="deleteStack(props.item.rancher_stack_id)" v-on="on">
                  <v-icon dark>delete</v-icon>
                </v-btn>
              </template>
              <span>Delete Stack in Database</span>
            </v-tooltip>
          </td>
        </template>
      </v-data-table>
    </v-card>
  </v-app>
</template>

<script>
export default {
  name: "StackRancherComponent",
  mounted() {
    this.instance = axios.create({
      baseURL: '/tiketux/rancher/stack/api/'
    });
    this.list();
  },
  data() {
    return {
      editStack:false,
      stacks:[],
      header:[
        { text: "ID Rancher ", value: "rancher_stack_id" },
        { text: "Remark", value: "remark" },
        { text: "Tanggal", value: "created_at" },
        { text: "Action", value: "id" }
      ]
    };
  },

  methods: {
     list: function(){
      var that = this;
      that.instance
        .get('liststackdb')
        .then(response => {
          that.stacks = response.data.data;
          console.log(response.data);
        })
        .catch(error => {
          console.log(response.data);                        
        });              
    },
    formEditStack: function(params){
      var that = this;
      that.id_stack = params;
      that.instance
        .post('cekstackdb', {
          "id_stack" : params
        })
        .then(response => {
          that.detailstack = response.data.data;
          that.rancherStackId = response.data.data.rancher_stack_id;
          that.remarkStack    = response.data.data.remark; 
          that.editStack = true;
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
          "stack_id" : that.rancherStackId,
          "remark" : that.remarkStack
        })
        .then(response => {
          console.log(response);
          that.editStack = false; 
          that.list();
        })
        .catch(error => {
          console.log(response.data);                        
        }); 
    },
    deleteStack: function(params){
      var that = this;
      that.instance
        .post('deletestackdb',{
          "stack_id" : params
        })
        .then(response => {
          console.log(response);
          that.list();
        })
        .catch(error => {
          console.log(response.data);                        
        }); 
    }
  }
  
};
</script>
