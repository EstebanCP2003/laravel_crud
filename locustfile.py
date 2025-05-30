from locust import HttpUser, task, between

# Esta clase define un usuario de prueba que simula el comportamiento de un cliente
class MyUser(HttpUser):
    # Define el tiempo de espera entre las tareas
    wait_time = between(1, 5) # devuelve un valor aleatorio en segundos que hara que el usuario espere entre 1 y 5 segundos entre tareas

    # Define una tarea que se ejecutará durante la prueba
    @task
    def index(self):
        # Realiza una solicitud GET a la ruta raíz del servidor
        self.client.get("/tasks")   
