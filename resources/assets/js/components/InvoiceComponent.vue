<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-block">
                    <div class="card-title-block">
                        <div class="float-left">
                            <h3 class="title"> Autorización para esta factura</h3>
                        </div>
                        <div class="dataTables_filter float-right form-inline mb-3 mt-0">
                            <label class="mr-2">Buscar:</label>
                            <input type="search" class="form-control form-control-sm" v-model="search"
                                v-on:keydown.enter.prevent="searchAuthorization()"
                            >
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                            <th>Código</th>
                            <th>EPS</th>
                            <th>Usuario</th>
                            <th>Desde</th>
                            <th>Total Días</th>
                            <th>Opciones</th>
                            </thead>
                            <tbody v-if="authorizationsTable.length > 0">
                                <tr v-for="element in authorizationsTable" :key="element.id">
                                    <td> <a :href="'/authorization/' + element.id + '/edit'"  target="_blank">{{ element.code }}</a></td>
                                    <td>{{ element.eps.name }}</td>
                                    <td>{{ element.patient.first_name + ' ' + element.patient.last_name }}</td>
                                    <td>{{ element.date_from | moment }}</td>
                                    <td>{{ element.date_from | days(element.date_to) }}</td>
                                    <td>
                                        <button class="btn btn-oval btn-success" type="button"
                                            @click="addAuthorization(element)"
                                        >
                                            Seleccionar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td class="text-center" colspan="6">No hay resultados que mostrar</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="beginning" class="col-md-12">
            <div class="card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group ">
                                <label for="number" class="control-label">Número de factura</label> 
                                <input placeholder="Número de factura" min="1" name="number" type="number" :value="number" id="number" class="form-control underlined">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="created_at" class="control-label">Fecha de la factura</label>
                                <input placeholder="dd/mm/aaaa" name="created_at" type="date" :value="now()" id="created_at" class="form-control underlined">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label for="company_id" class="control-label">Compañía a la que pertenece la factura</label>
                                <select id="company_id" name="company_id" class="form-control">
                                    <option v-for="company in JSON.parse(this.companies)" 
                                        :value="company.id" :key="company.id">
                                        {{ company.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-block">
                    <div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="multiple_table">
                                <thead>
                                <tr>
                                    <th>Autorización</th>
                                    <th>Servicios</th>
                                    <th style="width: 100px;">Días</th>
                                    <th>Total</th>
                                    <th style="min-width: 160px;">Acciones</th>
                                </tr>
                                </thead>
                                <tbody v-if="selectedAuthorizations.length > 0">
                                    <tr v-for="authorization in selectedAuthorizations" :key="authorization.id">
                                        <td class="pt-3">
                                            {{ authorization.code }}
                                            <input type="hidden" name="multiple_codes[]" :value="authorization.code">
                                        </td>
                                        <td class="pt-3">
                                            <div v-for="service in authorization.services" :key="service.id">
                                                {{ service.service.code }} - {{ service.price }}
                                            </div>
                                            <input type="hidden" name="multiple_price[]" :value="authorization.services[0].price">
                                        </td>
                                        <td class="pt-3">
                                            <div v-for="service in authorization.services" :key="service.id">
                                                {{ service.days }}
                                            </div>
                                            <input type="hidden" name="multiple_days[]" :value="authorization.services[0].days"
                                                :id="'multiple_days' + authorization.id"
                                            >
                                        </td>
                                        <td class="pt-3">
                                            <div v-for="service in authorization.services" :key="service.id">
                                                {{ service.days * parseInt(service.price) }}
                                            </div>
                                            <input type="hidden" name="multiple_totals[]" :value="authorization.services[0].days * parseInt(authorization.services[0].price)"
                                                :id="'multiple_totals' + authorization.id"
                                            >
                                        </td>
                                        <td>                                        
                                            <button class="btn btn-oval btn-danger" type="button"
                                                @click="removeAuthorization(authorization)"
                                            >
                                                Quitar
                                            </button>
                                            <button class="btn btn-oval btn-secondary" type="button"
                                                @click="updateModal(authorization)"
                                            >
                                                Modificar
                                            </button>
                                        </td>
                                        <div class="modal fade" :id="'update-modal' + authorization.id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            <i class="fas fa-cogs"></i>
                                                            Servicios para Autorización: {{ authorization.code }}
                                                        </h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row justify-content-center">
                                                            <div class="col-md-12">
                                                                <div class="text-danger" :id="'modal-error' + authorization.id"></div>
                                                                <div class="text-success" :id="'modal-success' + authorization.id"></div>
                                                                <br>
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover table-bordered">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Servicio</th>
                                                                            <th>Precio</th>
                                                                            <th style="width: 100px;">Días</th>
                                                                            <th>Total</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody v-for="service in authorization.services" :key="service.id">
                                                                            <td class="pt-3">
                                                                                {{ service.service.code + ' - ' + service.service.name }}
                                                                                <input type="hidden" name="service_codes[]" :id="'service_codes' + authorization.id + '_' + service.id"
                                                                                    class="form-control" :value="service.service.code"
                                                                                >
                                                                            </td>
                                                                            <td class="pt-3">
                                                                                {{ service.price }}
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" min="1" name="service_days[]" 
                                                                                    class="form-control" :value="service.days" :id="'service_days' + authorization.id + '_' + service.id"
                                                                                    v-on:keydown.enter.prevent="updateTotal($event, service.price, authorization.id + '_' + service.id)"
                                                                                    @change="updateTotal($event, service.price, authorization.id + '_' + service.id)"
                                                                                >
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" min="1" name="service_totals[]" readonly :id="'service_totals' + authorization.id + '_' + service.id"
                                                                                    class="form-control" :value="service.days * parseInt(service.price)"
                                                                                >
                                                                            </td>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="text-center">
                                                                    <button class="btn btn-oval btn-warning" type="button" @click="updateServices(authorization)">
                                                                        Actualizar
                                                                    </button>                                                                    
                                                                </div>
                                                            </div>                                
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-danger">
                                {{ errorSelected }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'number', 'companies', 'codes', 'days', 'totals'
        ],
        data() {
            return {
                search: '',
                authorizationsTable: [],
                selectedAuthorizations: [],
                errorSelected: ''
            };
        },
        methods: {
            searchAuthorization() {
                axios.post('/open-authorizations', { search: this.search})
                    .then((response) => {
                        this.authorizationsTable = response.data.data;
                    })
                    .catch((error) => console.log(error));
            },
            now() {
               return moment().format("YYYY-MM-DD"); 
            },
            addAuthorization(authorization) {
                this.errorSelected = '';
                
                if (this.selectedAuthorizations.includes(authorization)) {
                    this.errorSelected = 'Ya la autorización ' + authorization.code + ' fue añadida a la factura';
                    return;
                }

                this.selectedAuthorizations.push(authorization);
                document.querySelector('#beginning').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                history.pushState(null, null, '#beginning');
            },
            removeAuthorization(authorization) {
                this.selectedAuthorizations.splice(this.selectedAuthorizations.indexOf(authorization), 1);
            },
            updateModal(authorization) {
                $('#update-modal' + authorization.id).modal('show');
            },
            updateTotal(event, price, id) {
                $('#service_totals' + id).val(event.target.value * price);
            },
            updateServices(authorization) {
                var codes = [];
                var days = [];
                var totals = [];

                authorization.services.forEach(service => {
                    codes.push($('#service_codes' + authorization.id + '_' + service.id).val());
                    days.push($('#service_days' + authorization.id + '_' + service.id).val());
                    totals.push($('#service_totals' + authorization.id + '_' + service.id).val());

                    service.days = $('#service_days' + authorization.id + '_' + service.id).val();
                });

               const form = {
                   'authorization_id': authorization.id,
                   'services_quantity': authorization.services.length,
                   'service_codes[]': codes,
                   'service_days[]': days,
                   'service_totals[]': totals
                };

                axios.post('/authorization-services-update', form)
                    .then(response => {
                        this.selectedAuthorizations.forEach(currentAuthorization => {
                            if (currentAuthorization.code === authorization.code) {
                                currentAuthorization = authorization;
                                $('#multiple_days' + authorization.id).val(authorization.services[0].days);
                                $('#multiple_totals' + authorization.id).val(authorization.services[0].days * parseInt(authorization.services[0].price));
                            }
                        });
                        $('#modal-success' + authorization.id).html('<h3>Actualizando...</h3>')
                        $('#modal-success' + authorization.id).addClass('animated bounce pb-3');
                        setTimeout(function () { 
                            $('#update-modal' + authorization.id).modal('hide'); 
                            $('#modal-success' + authorization.id).html('')
                            $('#modal-success' + authorization.id).removeClass('animated bounce pb-3');
                        }, 1500);
                    })
                    .catch(error => console.log(error));
            },
            loadOldValues() {
                const oldCodes = JSON.parse(this.codes);
                const oldDays = JSON.parse(this.days);
                const oldTotals = JSON.parse(this.totals);
       
                if (oldCodes.length > 0) {                    
                    oldCodes.forEach((element, index) => {
                        axios.get('/get-authorization/' + element)
                            .then(response => {
                                this.selectedAuthorizations.push(response.data);
                            })
                            .catch(error => console.log(error));
                    });
                }
            }            
        },
        mounted() {
            this.searchAuthorization();
            this.loadOldValues();
        }
    }
</script>
