<template>
    <div>
        <div class="form-group" v-if="pucs.length > 0">
            <label for="pucs" class="control-label">Listado de códigos PUC</label>
            <select class="form-control" name="pucs" @change="updateAddTable($event)">
                <option>Seleccione el código PUC</option>
                <option :value="puc.code" v-for="puc in pucs" :key="puc.code">
                    {{ puc.code }} - {{ puc.description }}
                </option>
            </select>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-12">
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 135px !important;">Código</th>
                            <th style="">Descripción</th>
                            <th style="width: 150px !important;">Débitos</th>
                            <th style="width: 150px !important;">Créditos</th>
                            <th style="width: 85px !important;">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" v-model="addTable.code" class="form-control" placeholder="Código PUC" />
                                </td>
                                <td>
                                    <input type="text" v-model="addTable.description" placeholder="Descripción" class="form-control">
                                </td>
                                <td>
                                    <input type="number" min=0 v-model="addTable.debit" placeholder="Débitos" class="form-control">
                                </td>
                                <td>
                                    <input type="number" min=0 v-model="addTable.credit" placeholder="Créditos" class="form-control">
                                </td>
                                <td>
                                    <button @click.prevent="addPuc" class="btn btn-oval btn-info">Añadir</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6 text-danger">{{ addTableError }}</div>
                <div class="col-md-6 text-right">
                    <p>Totales: <span class="text-info">Débitos $ {{ debits.toLocaleString('es-ES') }}</span> | <span class="text-warning">Créditos $ {{ credits.toLocaleString('es-ES') }}</span></p>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="pucs" class="control-label">Códigos PUC del comprobante</label>
            <div class="row">
                <div class="col-12">
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 135px !important;">Código</th>
                            <th style="">Descripción</th>
                            <th style="width: 150px !important;">Débitos</th>
                            <th style="width: 150px !important;">Créditos</th>
                            <th style="width: 85px !important;">Acción</th>
                        </tr>
                        </thead>
                        <tbody v-if="pucTable.length > 0">
                            <tr v-for="(element, index) in pucTable">
                                <td>
                                    <input type="text" name="notePucs[]" v-model="element.code" class="form-control" placeholder="Código PUC" />
                                </td>
                                <td>
                                    <input type="text" name="pucDescription[]" v-model="element.description" placeholder="Descripción" class="form-control">
                                </td>
                                <td>
                                    <input type="number" min=0 name="pucDebit[]" v-model="element.debit" placeholder="Débitos" class="form-control" @change="updateDebits()">
                                </td>
                                <td>
                                    <input type="number" min=0 name="pucCredit[]" v-model="element.credit" placeholder="Créditos" class="form-control" @change="updateCredits()">
                                </td>
                                <td>
                                    <button @click.prevent="removePuc(index)" class="btn btn-oval btn-danger">Quitar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 text-danger">{{ pucTableError }}</div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            errors: {
                required: true
            }, 
            notePucs: {
                required: false
            }, 
            pucDescription: {
                required: false
            }, 
            pucDebit: {
                required: false
            }, 
            pucCredit: {
                required: false
            }
        },
        data() {
            return {
                pucs: [],
                addTable: {
                    code: '',
                    description: '',
                    debit: 0,
                    credit: 0
                },
                addTableError: '',
                pucTable: [],
                pucTableError: '',
                debits: 0,
                credits: 0,
                oldNotePucs: [], 
                oldPucDescription: [], 
                oldPucDebit: [],
                oldPucCredit: [],
                i: 0
            }
        },
        methods: {
            getPucs() {
                axios.get('/api/pucs')
                    .then((data) => {
                        this.pucs = data.data;
                    })
                    .catch(() => console.log('Some error on pucs'));
            },
            addPuc() {
                this.addTableError = '';
                if (this.invalidData()) {
                   this.addTableError = 'Por favor llena los campos necesarios';
                   return; 
                }
                this.debits += parseInt(this.addTable.debit);
                this.credits += parseInt(this.addTable.credit);
                this.pucTable.push(this.addTable);
                this.resetAddTable();
            },
            removePuc(index) {
                this.debits -= parseInt(this.pucTable[index].debit);
                this.credits -= parseInt(this.pucTable[index].credit);
                this.pucTable.splice(index, 1);
            },
            updateAddTable(event) {
                this.addTable.code = event.target.options[event.target.selectedIndex].value;
                this.addTable.description = event.target.options[event.target.selectedIndex].text.split(' - ')[1];
            },
            resetAddTable() {
                this.addTable = {
                    code: '',
                    description: '',
                    debit: 0,
                    credit: 0
                };
            },
            updateDebits() {
                this.debits = 0;
                this.pucTable.forEach(element => {
                    this.debits += parseInt(element.debit);
                });
            },
            updateCredits() {
                this.credits = 0;
                this.pucTable.forEach(element => {
                    this.credits += parseInt(element.credit);
                });
            },
            invalidData() {
                return (this.addTable.debit == 0 && this.addTable.credit == 0) ||
                    (this.addTable.debit == '' && this.addTable.credit == '') || 
                    this.addTable.description == '' || this.addTable.code == '';
            },
            loadOldValues() {
                this.oldNotePucs = JSON.parse(this.notePucs);
                this.oldPucDescription = JSON.parse(this.pucDescription);
                this.oldPucDebit = JSON.parse(this.pucDebit);
                this.oldPucCredit = JSON.parse(this.pucCredit);

                if (this.oldNotePucs.length > 0) {
                    this.oldNotePucs.forEach((element, index) => {
                        this.pucTable.push({
                            code: element,
                            description: this.oldPucDescription[index],
                            debit: this.oldPucDebit[index],
                            credit: this.oldPucCredit[index]
                        });
                        this.debits += parseInt(this.oldPucDebit[index]);
                        this.credits += parseInt(this.oldPucCredit[index]);
                    });
                }
            }
        },
        mounted() {
            this.getPucs();
            this.loadOldValues();
        }
    }
</script>
