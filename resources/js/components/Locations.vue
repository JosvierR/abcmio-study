<template>
    <section>
        <div class="form-group">
            <div class="col-xs-6">
                <label for="country_id"><h4>País <small class="required">*</small></h4>
                    <select name="country"  id="country_id" class="selectpicker form-control" data-live-search="true"  v-model="countrySelected" @change="setCountryId">
                        <option value="-1" :key="-1">-Seleccione un País-</option>
                        <option  v-for="option in countries" v-bind:value="option.id" :key="option.id">
                            {{option.name}}
                        </option>
                    </select>
                </label>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-6">
                <label for="city_id"><h4>Ciudad <small class="required">*</small></h4></label>
                <select name="city"  id="city_id" class="form-control" data-live-search="true" v-model="citySelected">
                    <option disabled value="-1">-Seleccione una  ciudad-</option>
                    <option  v-for="option in cities" v-bind:value="option.id" :key="option.id">
                        {{option.name}}</option>
                </select>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        name: "Locations",
        data(){
            return {
                countries:[],
                countrySelected:1,
                citySelected:'-1',
                cities:[],
                // city:{
                //     id:'',
                //     name:''
                // },
            }
        },
        props:['all_countries','city'],
        mounted(){
            this.countries = this.all_countries;
            // this.citySelected = this.city_id;
            // console.log("City ID:",this.citySelected);
            // $("#city_id").selectpicker("refresh");
            // console.log("Mounted MEthod");
            if(this.city)
            {
                this.countrySelected = this.city.country.id;
                this.searchCityByCountryId(this.city.country.id);
                this.citySelected = this.city.id;
            }
        },

        created() {
            if(!this.city)
            {
                // console.log("Created MEthod");
                this.searchCityByCountryId(this.countrySelected);
            }
            $("#city_id").selectpicker("refresh");
        },
        methods:{
            setCountryId()
            {
                // console.log(this.selectedValue);
                this.searchCityByCountryId(this.countrySelected)

            },
            searchCityByCountryId(id)
            {
                this.cities = [];
                window.axios.get(`/api/cities/${id}`).then(({data})=>{
                    // console.log(data);
                    if(data.success)
                    {
                        this.cities = data.result;
                    }else{
                        alert("Error no tiene ciudades");
                    }
                }).then(()=>{
                    $("#city_id").selectpicker("refresh");

                });


            }
        }
    }
</script>

<style scoped>

</style>
