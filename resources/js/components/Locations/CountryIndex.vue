<template>
    <section>
        <div class="card">
            <div class="card-header">
                Paises ({{total}})
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class=" table table-bordered table-striped table-hover datatable" id="country_table">
                        <thead>
                        <tr>
                            <th >
                                Nombre
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr  v-for="country in countries"  :key="country.id">
                            <td>
                                <a :href="`/admin/countries/`+country.id+'/edit'">{{country.name}}</a>
                            </td>

                            <td>
                                <a :href="`/admin/countries/`+country.id">Ver ciudades</a>
                            </td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        name: "CountryIndex",
        data(){
            return {
                countries:[],
                country:{
                    id:'',
                    name:''
                },
                total:0
            }
        },
        mounted() {
            this.fetchCountries();
        },
        created(){

        },
        methods:{
            fetchCountries(page_url){
                let vm = this;
                page_url = page_url || '/api/admin/countries';
                this.properties = [];
                window.axios.get(page_url).then(({data})=>{
                    // console.log(data);
                    // console.log(data);
                    // console.log(data.result.data);
                    if(data.success)
                    {
                        this.countries = data.result.data;
                        this.total = data.result.total;
                        // vm.makePagination(data.meta,data.links)
                        // this.cities = data.result;

                    }else{
                        // alert("No se encontro Propiedades");
                    }
                }).then(()=>{
                    // $("#city_id").selectpicker("refresh");
                    if($("#country_table").length)
                    {
                        $("#country_table").DataTable({
                            ordering:  true,
                            pageLength: 300
                        });
                    }

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
