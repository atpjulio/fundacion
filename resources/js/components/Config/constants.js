export const applicationInfo = {
  name: 'Casa Hogar'
}

export const documentTypes = {
  CC: "CC - Cédula de Ciudadanía",
  TI: "TI - Tarjeta de Identidad",
  CE: "CE - Cédula de Extranjería",
  PA: "PA - Pasaporte",
  RC: "RC - Registro Civil",
  CN: "CN - Certificado de Nacimiento",
  AS: "AS - Adulto Sin Identificación",
  MS: "MS - Menor Sin Identificación",
  NU: "NU - Número Unico de Identificación",
  PE: "PE - Permiso Especial",
  SC: "SC - Salvo Conducto",
}

export const documentTypesForSelect = Object.entries(documentTypes)
  .map(option => {
    return { value: option[0], name: option[1] }
  })

export const companyDocumentTypes = {
  NI: "NIT",
  CC: "Cédula de Ciudadanía",
  CE: "Cédula de Extranjería",
  PA: "Pasaporte",
}

export const companyDocumentTypesForSelect = Object.entries(companyDocumentTypes)
.map(option => {
  return { value: option[0], name: option[1] }
})

export const statusTypes = {
  ACTIVE: "Activo",
  INACTIVE: "Inactivo"
}

export const statusForSelect = Object.entries(statusTypes)
.map(option => {
  return { value: option[0], name: option[1] }
})

export const statusFilterTypes = {
  0: "Todos",
  ACTIVE: "Activo",
  INACTIVE: "Inactivo"
}

export const statusFilterForSelect = Object.entries(statusFilterTypes)
.map(option => {
  return { value: option[0], name: option[1] }
})

export const genderTypes = {
  F: "Femenino",
  M: "Masculino"
}

export const genderTypesForSelect = Object.entries(genderTypes)
.map(option => {
  return { value: option[0], name: option[1] }
})

export const zoneTypes = {
  U: "Urbana",
  R: "Rural"
}

export const zoneTypesForSelect = Object.entries(zoneTypes)
.map(option => {
  return { value: option[0], name: option[1] }
})

export const patientTypes = {
  SUBSIDIZED: "Contributivo",
  CONTRIBUTORY: "Subsidiado",
  LINKED: "Vinculado",
  PARTICULAR: "Particular",
  OTHER: "Otro",
  DISPLACED_CONTRIBUTORY: "Desplazado Contributivo",
  DISPLACED_SUBSIDIZED: "Desplazado Subsidiado",
  DISPLACED_UNINSURED: "Desplazado No Asegurado",
}

export const patientTypesForSelect = Object.entries(patientTypes)
.map(option => {
  return { value: option[0], name: option[1] }
})
