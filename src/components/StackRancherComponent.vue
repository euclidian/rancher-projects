<template>
  <v-app>
    <v-card v-if="dialogFormService">
        <v-btn
        color="white"
        class="mb-0 black--text"
        fab
        small
        @click="dialogFormService=false"
      >
        <v-icon dark>close</v-icon>
      </v-btn>
        <v-card-title>
          <span class="headline">Edit Service Stack</span>
        </v-card-title>
        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12>
                <v-text-field
                  v-model="rancherProjectId"
                  :counter="10"
                  label="Name"
                  required
                  readonly
                ></v-text-field>
                <v-text-field
                  v-model="gitURL"
                  :counter="10"
                  label="Name"
                  required
                  readonly
                ></v-text-field>
                <v-textarea
                  v-model="remarkService"
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
          <v-btn color="error" flat @click="dialogFormService = false">Batal</v-btn>
          <v-btn color="primary" flat @click="editService">Simpan</v-btn>
        </v-card-actions>
      </v-card>
    <v-card v-if="dialogService == true && dialogFormService == false">
        <v-btn
        color="white"
        class="mb-0 black--text"
        fab
        small
        @click="dialogService =false"
      >
        <v-icon dark>close</v-icon>
      </v-btn>
        <v-card-title>
          <span class="headline">List Service Stack</span>
        </v-card-title>
        <v-data-table
          :rows-per-page-items="rowsPerPageItems"
          :pagination.sync="pagination"
          :headers="headerDetail"
          :items="detailServiceStackonDB"
          class="elevation-1"
        >
          <template v-slot:items="props">
            <td>{{ props.item.rancher_project_id }}</td>
            <td>{{ props.item.gitlab_url }}</td>
            <td>{{ props.item.remark }}</td>
            <td>{{ props.item.created_at }}</td>
            <td>
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-btn fab dark small color="warning"  @click="formEditService(props.item.id)" v-on="on">
                    <v-icon dark>edit</v-icon>
                  </v-btn>
                </template>
                <span>Edit Stack in Database</span>
              </v-tooltip>
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-btn fab dark small color="error"  @click="deleteService(props.item.id)" v-on="on">
                    <v-icon dark>delete</v-icon>
                  </v-btn>
                </template>
                <span>Delete Stack in Database</span>
              </v-tooltip>
            </td>
          </template>
        </v-data-table>
      </v-card>
    <v-card v-if="editStack == true && dialogService == false && dialogFormService == false">
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
    <v-card v-else-if="editStack == false && dialogService == false && dialogFormService == false">
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
          <td>{{ props.item.name }}</td>
          <td>{{ props.item.remark }}</td>
          <td>{{ props.item.created_at }}</td>
          <td>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="success"  @click="detailServiceStack(props.item.id)" v-on="on">
                  <v-icon dark>more_horiz</v-icon>
                </v-btn>
              </template>
              <span>Detail Stack</span>
            </v-tooltip>
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
      rowsPerPageItems: [25, 50, 75, 100],
      pagination: {
        rowsPerPage: 25
      },
      editStack:false,
      stacks:[],
      header:[
        { text: "ID Stack ", value: "rancher_stack_id" },
        { text: "Name", value: "name" },
        { text: "Remark", value: "remark" },
        { text: "Tanggal", value: "created_at" },
        { text: "Action", value: "id" }
      ],
      headerDetail:[
        { text: "ID Rancher ", value: "rancher_project_id" },
        { text: "Gitlab URL", value: "gitlab_url" },
        { text: "Remark", value: "remark" },
        { text: "Tanggal", value: "created_at" },
        { text: "Action", value: "rancher_project_id" }
      ],
      dialogService:false,
      dialogFormService:false
    };
  },

  methods: {
     list: function(){
      var that = this;
      that.instance
        .get('liststackdb')
        .then(response => {
          that.stacks = response.data.data;
          that.stacks.forEach(function(item, index) {
            that.$set(that.stacks[index], "name", null);
            that.detail(index, item);
          });
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
    },
    detail: function(index, item){
      var that = this;
      that.instance
      .post('detailstack',{
        "stack_id" : item.rancher_stack_id
      })
      .then(response => {
        that.stacks[index].name = response.data.data.name;
        console.log(response.data.data);
      })
      .catch(error => {
        console.log(response.data);                        
      }); 
    },
    detailServiceStack: function(params){
      var that = this;
      that.instance
      .post('detailservicestackdb',{
        "stack_id" : params
      })
      .then(response => {
        that.detailServiceStackonDB = response.data.data;
        that.dialogService = true;
        console.log(response.data.data);
      })
      .catch(error => {
        console.log(response.data);                        
      });
    },
    formEditService :function(params){
      var that = this;
      that.instance
        .post('detailservice',{
          "id" : params
        })
        .then(response => {
          that.dialogFormService = true;
          that.idService = response.data.data.id;
          that.gitURL = response.data.data.gitlab_url;
          that.rancherProjectId = response.data.data.rancher_project_id;
          that.remarkService = response.data.data.remark;
          that.stackIdService = response.data.data.stack_id;
          console.log(response);
        })
        .catch(error => {
          console.log(response.data);                        
        });
    },
    deleteService : function(params){
      var that = this;
      that.instance
        .post('deleteservicestackdb',{
          "id" : params
        })
        .then(response => {
          that.detailServiceStack(that.stackIdService);
        })
        .catch(error => {
          console.log(response.data);                        
        });
    },
    editService: function(){
      var that = this;
      that.instance
        .post('updateservicetodb',{
          "id" : that.idService,
          "url" : that.gitURL,
          "project_id" : that.rancherProjectId,
          "remark" : that.remarkService,
          "stack_id" : that.stackIdService
        })
        .then(response => {
          that.dialogFormService =false;
          that.detailServiceStack(that.stackIdService);
          that.dialogService = true;
        })
        .catch(error => {
          console.log(response.data);                        
        });
    }
  }
  
};
</script>
