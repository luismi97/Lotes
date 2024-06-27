let currentLote = null

const addDataAttributes = (data) => {
  data.forEach(item => {
    const element = document.getElementById(item.ID);
    if (element) {
      const estado = item.estado === "Reservado" ? "Amarrado" : item.estado
      element.dataset.precioMc = item.precio_m2;
      element.dataset.superficieTotal = item.superficie_total;
      element.dataset.metroFondo = item.metros_fondo;
      element.dataset.metroFrente = item.metros_frente;
      element.dataset.numeroFilial = item.filial;
      element.dataset.lote = item.lote;
      element.dataset.seccion = item.seccion;
      element.dataset.estado = estado;

      element.classList.add(estado.toLowerCase())
    }
  });
};

const addValuesToLotes = () => {
  const lotesValues = document.getElementById('infoLotes')
  const lotesToArray = JSON.parse(lotesValues.dataset.infolotes)
  addDataAttributes(lotesToArray)
};

const modal = document.getElementById("modal");
const modalWrapper = document.getElementById("modal-wrapper")
const whatsText = document.getElementById("modal-whats-text")
const avialableLote = "Esta propiedad está disponible en su totalidad. Si desea obtener más información o programar una cita para verla, puede contactarnos a través de WhatsApp."
const notAvialableLote = "Esta propiedad no está disponible. Si desea obtener más información o programar una cita para ver otras propiedades, puede contactarnos a través de WhatsApp."

const clearClass = () => modalWrapper?.classList?.remove("disponible", "amarrado", "vendido");

const extractDataAttributes = (event) => ({ ...event.target.dataset });

const assignValuesToModalElements = (dataObj) => {
  Object.entries(dataObj).forEach(([key, value]) => {
    const modalElement = document.getElementById(`modal-${key}`);
    if (modalElement) {
      modalElement.textContent = value;
      if(value === "Disponible") {
        whatsText.textContent = avialableLote
      } else if(value === "Amarrado" || value === "Vendido") {
        whatsText.textContent = notAvialableLote
      }
    }
  });
};

const handleLoteClick = (e) => {
  modal.classList.remove("hide");
  clearClass();
  const loteValues = extractDataAttributes(e);
  currentLote = loteValues
  assignValuesToModalElements(loteValues);
  const estado = loteValues.estado;
  if (["Amarrado", "Disponible", "Vendido"].includes(estado)) {
    modalWrapper.classList.add(estado.toLowerCase());
  }
};

document.querySelectorAll(".lote").forEach((lote) => lote.addEventListener('click', handleLoteClick));

document.getElementById("modal-close-icon").addEventListener("click", () => modal.classList.add("hide"));

// Llama a la función addValuesToLotes antes de ejecutar la lógica existente
addValuesToLotes();

const contactButton = document.getElementById('whatsapp-button')
const contactButtonMore = document.getElementById('whatsapp-button-first')
const whatsappHandler = (e) => {
  const phoneNumber = e.target.dataset.phone;
  const contact = e.target.dataset.contact;

  if(contact === "true") {
    window.open('https://api.whatsapp.com/send/?phone=' + phoneNumber + '&text=Estimado/a, deseo expresar mi interés en el proyecto de Distrito San Juan. Agradecería si pudiéramos discutir más detalles al respecto. Quedo a la espera de su pronta respuesta.', '_blank');
  } else if (currentLote.estado === "Disponible") {
    window.open('https://api.whatsapp.com/send/?phone=' + phoneNumber + '&text=Estimado/a, deseo expresar mi interés en la adquisición del lote: ' + currentLote.lote + ', sección: ' + currentLote.seccion + ', dentro del Condominio Distrito San Juan. Agradecería si pudiéramos discutir más detalles al respecto. Quedo a la espera de su pronta respuesta.', '_blank');
  } else {
    window.open('https://api.whatsapp.com/send/?phone=' + phoneNumber + '&text=Estimado/a, quiero informarle que estuve interesado en el lote: ' + currentLote.lote + ', sección: ' + currentLote.seccion + ', dentro del Condominio Distrito San Juan. Sin embargo, parece que este lote no está disponible en este momento. Agradezco cualquier información adicional que pueda proporcionar sobre opciones similares disponibles. Quedo a la espera de su respuesta.', '_blank');
  }
}

contactButton.addEventListener("click", whatsappHandler);
contactButtonMore.addEventListener("click", whatsappHandler);