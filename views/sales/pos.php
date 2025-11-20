<br></br>
<style>
    /* Estilos específicos solo para el POS */
    #searchResults {
        max-height: 300px;
        overflow-y: auto;
        position: absolute;
        z-index: 1000;
        width: 96%; 
        display: none;
    }
    .pos-container {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
</style>

<div class="container-fluid mt-2">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-cart4"></i> Punto de Venta</h2>
        </div>

    <?php if (isset($_GET["ok"])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> Venta <strong>#<?= htmlspecialchars($_GET["ok"]) ?></strong> registrada.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-7">
            <div class="pos-container h-100">
                <h4 class="mb-3 text-primary">Buscar Producto</h4>
                <div class="position-relative">
                    <div class="input-group input-group-lg mb-3">
                        <span class="input-group-text bg-primary text-white"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchInput" class="form-control" 
                               placeholder="Escribe nombre, marca o SKU..." autocomplete="off">
                    </div>
                    <div id="searchResults" class="list-group shadow"></div>
                </div>
                
                <div class="alert alert-info mt-4">
                    <small><i class="bi bi-info-circle"></i> Busca el repuesto y haz clic para agregarlo al carrito.</small>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-basket"></i> Carrito</h5>
                    <span class="badge bg-warning text-dark" id="cartCount">0 items</span>
                </div>
                <div class="card-body p-0">
                    <form method="POST" action="/Repuestos/public/ventas/crear" id="saleForm">
                        <input type="hidden" name="payment_method" value="CASH">
                        
                        <div class="table-responsive" style="height: 400px; overflow-y: auto;">
                            <table class="table table-hover mb-0 align-middle" id="cartTable">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Producto</th>
                                        <th width="15%">Cant.</th>
                                        <th class="text-end">Subtotal</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    </tbody>
                            </table>
                        </div>

                        <div class="p-3 bg-light border-top">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 text-muted">Total a Pagar:</span>
                                <span class="h2 fw-bold text-success">Bs. <span id="total">0.00</span></span>
                            </div>
                            <button class="btn btn-success w-100 btn-lg py-3" id="btnSubmit" disabled>
                                CONFIRMAR VENTA <i class="bi bi-arrow-right-circle"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];
let tbody = document.querySelector("#cartTable tbody");
let totalEl = document.querySelector("#total");
let btnSubmit = document.querySelector("#btnSubmit");
let searchInput = document.querySelector("#searchInput");
let searchResults = document.querySelector("#searchResults");
let cartCount = document.querySelector("#cartCount");

// BÚSQUEDA
searchInput.addEventListener('keyup', function() {
    let query = this.value.trim();
    if (query.length < 2) { searchResults.style.display = 'none'; return; }

    fetch(`/Repuestos/public/productos/buscar-json?q=${query}`)
        .then(response => response.json())
        .then(data => {
            searchResults.innerHTML = '';
            if (data.length === 0) {
                searchResults.innerHTML = '<div class="list-group-item">No encontrado.</div>';
            } else {
                data.forEach(p => {
                    let stock = parseInt(p.quantity);
                    let item = document.createElement('a');
                    item.className = 'list-group-item list-group-item-action d-flex justify-content-between';
                    item.href = '#';
                    
                    let stockLabel = stock > 0 
                        ? `<span class="badge bg-success">${stock} u.</span>` 
                        : `<span class="badge bg-danger">Agotado</span>`;

                    item.innerHTML = `
                        <div><strong>${p.name}</strong><br><small class="text-muted">${p.sku}</small></div>
                        <div class="text-end"><div class="fw-bold">Bs. ${p.price}</div>${stockLabel}</div>
                    `;

                    if(stock > 0) {
                        item.onclick = (e) => {
                            e.preventDefault();
                            addToCart(p.id, p.name, p.price, p.cost, stock);
                            searchInput.value = '';
                            searchResults.style.display = 'none';
                            searchInput.focus();
                        };
                    }
                    searchResults.appendChild(item);
                });
            }
            searchResults.style.display = 'block';
        });
});

// CERRAR RESULTADOS AL CLICKEAR FUERA
document.addEventListener('click', e => {
    if (e.target !== searchInput) searchResults.style.display = 'none';
});

// CARRITO
function addToCart(id, name, price, cost, max) {
    let exist = cart.find(i => i.product_id === id);
    if (exist) {
        if (exist.qty < max) exist.qty++;
        else alert("Stock máximo alcanzado");
    } else {
        cart.push({ product_id: id, name, qty: 1, price, cost, maxStock: max });
    }
    renderCart();
}

function renderCart() {
    tbody.innerHTML = "";
    let total = 0;
    
    cart.forEach((item, i) => {
        total += item.qty * item.price;
        tbody.innerHTML += `
            <tr>
                <td><small class="fw-bold">${item.name}</small></td>
                <td>
                    <input type="number" class="form-control form-control-sm text-center p-1" 
                           value="${item.qty}" min="1" max="${item.maxStock}" 
                           onchange="updateQty(${i}, this.value)">
                </td>
                <td class="text-end">Bs. ${(item.qty * item.price).toFixed(2)}</td>
                <td>
                    <button class="btn btn-sm text-danger p-0" onclick="removeItem(${i})"><i class="bi bi-x-circle-fill fs-5"></i></button>
                </td>
            </tr>
        `;
    });

    totalEl.innerText = total.toFixed(2);
    cartCount.innerText = cart.length + " items";
    btnSubmit.disabled = cart.length === 0;

    // Generar inputs ocultos
    let form = document.querySelector("#saleForm");
    document.querySelectorAll(".dynamic").forEach(e => e.remove());
    cart.forEach((item, i) => {
        addHidden(form, `items[${i}][product_id]`, item.product_id);
        addHidden(form, `items[${i}][qty]`, item.qty);
        addHidden(form, `items[${i}][price]`, item.price);
        addHidden(form, `items[${i}][cost]`, item.cost);
    });
}

function addHidden(form, name, val) {
    let i = document.createElement("input");
    i.type = "hidden"; i.className = "dynamic"; i.name = name; i.value = val;
    form.appendChild(i);
}

function updateQty(i, val) {
    let v = parseInt(val);
    if (v > cart[i].maxStock) { alert("Max: " + cart[i].maxStock); cart[i].qty = cart[i].maxStock; }
    else if (v < 1) cart[i].qty = 1;
    else cart[i].qty = v;
    renderCart();
}

function removeItem(i) {
    cart.splice(i, 1);
    renderCart();
}
</script>