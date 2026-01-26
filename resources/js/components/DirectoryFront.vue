<template>
    <section id="directory-products">
        <ul>
            <li v-for="property in properties" :key="property.id">

               <article>
                    <div class="thumb">
                        <img src="/img/nopic.jpg"/>
                    </div>
                   <div>
                       <a v-bind:href="'/'+property.slug"><h3>{{property.title}}</h3></a>
                       <p>{{property.short_description}}</p>
                        <h4> {{(property.category.parent)?property.category.parent.name:'N/A'}} / {{property.category.name}}</h4>
                        <h5>Santiago de los Caballeros/ Republica Dominicana</h5>
                   </div>
               </article>
                <div class="clearFix"></div>
            </li>
        </ul>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="getDirectory(pagination.prev_page_url)">Previous</a></li>

                <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>

                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="getDirectory(pagination.next_page_url)">Next</a></li>
            </ul>
        </nav>
    </section>
</template>

<script>
    export default {
        name: "DirectoryFront",
        data(){
            return {
                pagination:{},
                properties:[],
                property:{
                    slug:'',
                    title:'',
                    category_id:'',
                    is_public:'',
                    action_id:'',
                    status:'',
                    website:'',
                    image_path:'',
                    short_description:'',
                    description:'',
                    comment:'',
                    phone:'',
                    show_email:'',
                    show_website:'',
                    serial_number:'',
                    google_map:'',
                    send_message:''
                },

            }
        },
        mounted() {
            this.getDirectory();
        },
        created() {
        },
        methods:{
            getDirectory(page_url){
                let vm = this;
                page_url = page_url || '/api/properties';
                this.properties = [];
                window.axios.get(page_url).then(({data})=>{
                    console.log(data);
                    if(data.data)
                    {
                        this.properties = data.data;
                        vm.makePagination(data.meta,data.links)
                        // this.cities = data.result;

                    }else{
                        // alert("No se encontro Propiedades");
                    }
                }).then(()=>{
                    // $("#city_id").selectpicker("refresh");

                });
            },
            makePagination(meta,links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url:links.prev
                }
                this.pagination = pagination
            }
        }
    }
</script>

<style scoped>

</style>
