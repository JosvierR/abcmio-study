<template>
    <section>
        <div class="form-group">
            <div class="col-xs-6">
                <label for="category_id"><h4>Categoría <small class="required">*</small></h4>
                    <select name="category"  id="category_id" class="selectpicker form-control" data-live-search="true"  v-model="categorySelected" @change="setCategoryId">
                        <option value="-1" :key="-1">-Seleccione un País-</option>
                        <option  v-for="option in categories" v-bind:value="option.id" :key="option.id">
                            {{option.name}}
                        </option>
                    </select>
                </label>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-6">
                <label for="child_id"><h4>Sub Categorías <small class="required">*</small></h4></label>
                <select name="child"  id="child_id" class="form-control" data-live-search="true" v-model="childSelected">
                    <option disabled value="-1">-Seleccione una  Sub Categoría-</option>
                    <option  v-for="option in children" v-bind:value="option.id" :key="option.id">
                        {{option.name}}</option>
                </select>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        name: "Categories",
        data(){
            return {
                categories:[],
                categorySelected:1,
                childSelected:'-1',
                children:[],
            }
        },
        props:['all_categories','child'],
        mounted(){
            this.categories = this.all_categories;
            if(this.child)
            {
                // console.log(this.child);
                this.searchServiceByCategoryId(this.child.parent.id);
                this.serviceSelected = this.child.id;
                this.categorySelected = this.child.parent.id;
                this.childSelected = this.child.id;
            }
        },

        created() {
            if(!this.city)
            {
                // console.log("Created MEthod");
                this.searchServiceByCategoryId(this.categorySelected);
            }
            $("#child_id").selectpicker("refresh");
        },
        methods:{
            setCategoryId()
            {
                // console.log(this.selectedValue);
                this.searchServiceByCategoryId(this.categorySelected)

            },
            searchServiceByCategoryId(id)
            {
                this.children = [];
                window.axios.get(`/api/category/children/${id}`).then(({data})=>{
                    // console.log(data);
                    if(data.success)
                    {
                        this.children = data.result;
                    }else{
                        alert("Error no tiene Sub Categoría");
                    }
                }).then(()=>{
                    $("#child_id").selectpicker("refresh");

                });


            }
        }
    }
</script>

<style scoped>

</style>
