<template>
        <div class="row">
          <div class="col-12">
         <div class="card">
                <div class="card-header text-center mb-3">
                    <h2>Customer Table</h2>
                    <h6>RestApi Using Vue.js</h6>
                </div>
                 <div class="mb-3">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                <strong>Search By :</strong>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="query" v-model="query" class="form-control" placeholder="Search Here">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" @click="reload">Reload</button>
                                <button class="btn btn-primary" @click="create">Add</button>
                            </div>
                        </div>
                    </div>
                <div class="card-body">
                      <div class="table-responsive">
                          <table class="table table-hover table-responsive">
                            <th>ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Post Code</th>
                            <th>Action</th>
                            <tbody>
                                <tr v-show="posts.length" v-for="(post, index) in posts" :key="post.id">
                                  
                                    <td>{{index+1}}</td>
                                    <td>{{post.name}}</td>
                                    <td>{{post.address}}</td>
                                    <td>{{post.number}}</td>
                                    <td>{{post.postcode}}</td>
                                    <td>
                                        <button class="btn btn-primary" @click="edit(post)">
                                            Edit
                                        </button>
                                        <button class="btn btn-danger" @click="deleteData(post)">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            
                            </tbody>
                      </table>
                      </div>

                      <div class="row"> 
                       <div class="col-12">           
<pagination-component v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="query === '' ? getData() : searchData()"></pagination-component>
                        </div>
                      </div>
                </div>
         </div>
           
              <!-- Modal -->
        <div class="modal fade" id="createModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{editMode ? "Edit" : "Add New"}} Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                      <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                    <div class="modal-body text-left">
                        <div class="form-group ">
                          <label>Customer Name</label>
                          <input v-model="form.name" type="text" name="name"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                          <has-error :form="form" field="name"></has-error>
                        </div> 
                        <div class="form-group">
                          <label>Customer Address :</label>
                          <input v-model="form.address" type="text" name="address"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('address') }">
                          <has-error :form="form" field="address"></has-error>
                        </div>
                         <div class="form-group">
                          <label>Customer Phone</label>
                          <input v-model="form.number" type="text" name="number"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('number') }">
                          <has-error :form="form" field="number"></has-error>
                        </div> 
                        
                         <div class="form-group">
                          <label>Customer Post Code</label>
                          <input v-model="form.postcode" type="number" name="postcode"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('postcode') }">
                          <has-error :form="form" field="postcode"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button :disabled="form.busy" type="submit" class="btn btn-primary">
                            {{editMode ? "Updated" : "Create New Customer"}}
                        </button>
                    </div>
                      </form>
                </div>
            </div>
        </div>
         <vue-snotify></vue-snotify>
       <vue-progress-bar></vue-progress-bar>
    </div>
</div>
</template>

<script>
    import axios from 'axios';
    export default {
        mounted() {
            console.log('Component mounted.')
            this.$Progress.start()
            this.getData();
        },

        data(){
            return{
                posts:[],
                form: new Form({
                    id:'',
                    name:'',
                    address:'',
                    number:'',
                    postcode:'',
                }),
                pagination: {
                        current_page: 1,
                    },
                    field: 'name',
                editMode:false,
                query:''
            }
        },
        watch: {
                query: function(newQ, old) {
                    if (newQ === '') {
                        this.getData();
                    } else {
                        // console.log(newQ)
                        this.searchData();
                    }
                }
            },

        methods:{
            getData(){
                axios.get('/api/customer?page=' + this.pagination.current_page)
                .then(posts => {
                    this.posts= posts.data.data
                     this.pagination = posts.data.meta
                    this.$Progress.finish()
                }).catch(e =>{
                    this.$Progress.fail()
                });
            },
             searchData() {
                        this.$Progress.start()
                        axios.get('/api/search/customer/'+ this.query).then(response => {
                            this.posts = response.data.data
                            this.$Progress.finish()
                        }).catch(e => {
                            this.$Progress.fail()

                        });
                       
                    },
            reload(){
               this.$Progress.start()
               this.getData()
                this.form.reset()
                this.form.clear()
               this.$Progress.finish()
            },
            create(){
                this.editMode = false
                this.form.reset()
                this.form.clear()
                $("#createModalLong").modal('show')
            },
            store(){
                this.$Progress.start()
                this.form.busy = true

                this.form.post('/api/customer').then(result => {
                    this.getData()
                    this.$Progress.finish()
                    $("#createModalLong").modal('hide')
                }).catch(e => {
                     this.$Progress.fail()
                })

            },
            edit(post){
               this.editMode = true
               this.form.reset
               this.form.clear
               this.form.fill(post)
               $("#createModalLong").modal('show')
            },

            update(){
                this.$Progress.start()
                this.form.busy = true
                this.form.put('/api/customer/'+this.form.id).then(result =>{
                    this.getData()
                    if (this.form.successful) {
                                this.$Progress.finish()
                                $("#createModalLong").modal('hide')
                            }else{
                                this.$Progress.fail()
                            }
                }).catch(e => {
                    this.$Progress.fail()
                })
            },

            deleteData(post){
                this.$Progress.start()
                axios.delete('/api/customer/'+post.id).then(result =>{
                    this.getData()
                    if (this.form.successful) {
                        this.$Progress.finish()
                        this.$snotify.success('Wrong', 'success')
                    }
                }).catch(e=>{
                    this.$Progress.fail()
                })
            }

        }
    }
</script>
