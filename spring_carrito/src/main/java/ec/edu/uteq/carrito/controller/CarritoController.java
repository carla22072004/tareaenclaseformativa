package ec.edu.uteq.carrito.controller;

import ec.edu.uteq.carrito.model.Producto;
import jakarta.servlet.http.HttpSession;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

import java.util.*;

@Controller
@RequestMapping("/carrito")
public class CarritoController {

    // Clave de la sesion
    private static final String CLAVE = "carrito";

    // Catalogo de precios validos (servidor, nunca del cliente)
    private static final Map<String, Double> PRECIOS = Map.of(
            "Laptop", 450.0,
            "Mouse", 25.0,
            "Teclado", 45.0,
            "Monitor", 180.0
    );

    // Helper: obtener el carrito de la sesion
    @SuppressWarnings("unchecked")
    private List<Producto> obtenerCarrito(HttpSession session) {
        List<Producto> carrito = (List<Producto>) session.getAttribute(CLAVE);
        if (carrito == null) {
            carrito = new ArrayList<>();
            session.setAttribute(CLAVE, carrito);
        }
        return carrito;
    }

    // GET /carrito -- mostrar catalogo y carrito
    @GetMapping
    public String index(HttpSession session, Model model) {
        List<Producto> carrito = obtenerCarrito(session);
        double total = carrito.stream()
                .mapToDouble(Producto::precio)
                .sum();

        model.addAttribute("carrito", carrito);
        model.addAttribute("catalogo", PRECIOS.keySet());
        model.addAttribute("total", total);
        model.addAttribute("sessionId", session.getId());
        return "carrito/index"; // templates/carrito/index.html
    }

    // POST /carrito/agregar -- agregar producto
    @PostMapping("/agregar")
    public String agregar(@RequestParam String nombre, HttpSession session) {
        if (PRECIOS.containsKey(nombre)) {
            obtenerCarrito(session).add(new Producto(nombre, PRECIOS.get(nombre)));
        }
        return "redirect:/carrito"; // PRG pattern
    }

    // POST /carrito/eliminar -- eliminar por indice
    @PostMapping("/eliminar")
    public String eliminar(@RequestParam int indice, HttpSession session) {
        List<Producto> carrito = obtenerCarrito(session);
        if (indice >= 0 && indice < carrito.size()) {
            carrito.remove(indice);
        }
        return "redirect:/carrito";
    }

    // POST /carrito/limpiar -- vaciar el carrito
    @PostMapping("/limpiar")
    public String limpiar(HttpSession session) {
        session.removeAttribute(CLAVE); // solo el carrito
        // Para cerrar sesion completo:
        // session.invalidate();
        return "redirect:/carrito";
    }
}
