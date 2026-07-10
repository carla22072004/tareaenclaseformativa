package ec.edu.uteq.carrito.model;

import java.io.Serializable;

// Serializable: necesario para almacenar en sesion
public record Producto(String nombre, double precio) implements Serializable {
    private static final long serialVersionUID = 1L;
}
