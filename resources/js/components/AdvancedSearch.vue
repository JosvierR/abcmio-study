<template>
    <section class="col-8 ">
            <fieldset class="row">
                    <div class="row form-group">
                        <div class="col-sm">
                            <select name="country"  id="country_id" class="selectpicker form-control" data-live-search="true"  v-model="country" @change="setCountryId">
                                <option value="-2" :key="-2">-Seleccione un País-</option>
                                <option value="-1" :key="-1">Todos los Paises</option>
                                <option  v-for="option in countries" v-bind:value="option.id" :key="option.id">
                                    {{option.name}}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm ">
                            <select  name="city" v-model="city"  id="city_id" class="form-control" data-live-search="true" :disabled="country<=0"  >
                                <option value="-2" :key="-2">-Seleccione Ciudad -</option>
                                <option value="-1" :key="-1">Todas las Ciudades</option>
                                <option  v-for="option in cities" v-bind:value="option.value" :key="option.id">
                                    {{option.name}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm">
                            <select name="category_id"  id="categories_id" class="selectpicker form-control" data-live-search="true"  v-model="categorySelected" @change="setCategoryId">
                                <option value="-2" :key="-2">-Seleccione Categoría-</option>
                                <option value="-1" :key="-1">Todas las Categoría</option>
                                <option  v-for="option in parents_categories" v-bind:value="option.id" :key="option.id">
                                    {{option.name}}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm">
                            <select  name="sub_category" v-model="subCategory"  id="sub_categories_id" class="form-control" data-live-search="true" :disabled="categorySelected == -1">
                                <option value="-1">-Seleccione Sub Categoría-</option>
                                <option if="categorySelected" v-for="option in categories" v-bind:value="option.value" :key="option.id">
                                    {{option.name}}</option>
                            </select>
                        </div>
                    </div>
            </fieldset>
            <fieldset class="row ">
                    <div class="row form-group">
                        <div class="col-sm-9">
                            <input class="form-control" name="query" type="text" placeholder="Search" />
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" name="btn_search" class="btn btn-block btn-primary">Buscar</button>
                        </div>
                    </div>
                    <div class="form-check mb-2 mr-sm-2">
                        <input class="form-check-input" type="checkbox" id="exact_match" name="exact_match">
                        <label class="form-check-label" for="exact_match">
                            Búsqueda Exacta
                        </label>
                    </div>
            </fieldset>
    </section>
</template>

<script>
    export default {
        name: "AdvancedSearch",
        data(){
            return {
                countries:[],
                country:-1,
                cities:[],
                citySelected:'',
                city:-1,
                category:'',
                categories:[],
                categorySelected:-1,
                subCategory:-1
            }
        },
        props:['all_countries','parents_categories','countrySelected'],
        mounted(){
            this.countries = this.all_countries;
            // this.selectedValue = this.countrySelected;
            // console.log(this.token);
            // console.log(this.parents_categories);
        },
        created() {
            this.searchCityByCountryId(this.country);
            this.searchSubCategoryByParentId(this.categorySelected);
        },
        methods:{
            setCountryId()
            {
                // console.log(this.selectedValue);
                this.searchCityByCountryId(this.country)

            },
            setCity()
            {
              console.log(this.city);
            },
            searchCityByCountryId(id)
            {
                this.cities = [];
                window.axios.get(`/api/cities/${id}`).then(({data})=>{
                    if(data.success)
                    {
                        this.cities = data.result;

                    }else{
                        alert("Error no tiene ciudades");
                    }
                }).then(()=>{
                    $("#city_id").selectpicker("refresh");

                });


            },
            setCategoryId()
            {
                // console.log(this.selectedValue);
                this.searchSubCategoryByParentId(this.categorySelected);

            },
            searchSubCategoryByParentId(id)
            {
                this.cities = [];
                window.axios.get(`/api/category/children/${id}`).then(({data})=>{
                    if(data.success)
                    {
                        this.categories = data.result;

                    }else{
                        alert("Error no tiene categorias");
                    }
                }).then(()=>{
                    $("#sub_categories_id").selectpicker("refresh");

                });


            }
        }
    }
</script>

<style scoped>

</style>
