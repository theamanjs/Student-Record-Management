<style>
.loader-container {
  height: 100%;
  width: 100%;
  display: none;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  background-color: rgba(0, 0, 0, 0.2);
  position: absolute;
  z-index: 99999;
}
.loader-card {
  height: 100px;
  width: 120px;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.2);
  flex-direction: column;
}
.loader {
  height: 35px;
  width: 35px;
  border: 2px solid #ccc;
  border-top: 2px solid #444;
  animation: spin infinite 0.8s linear;
  border-radius: 50%;
}
.loader-text {
  position: relative;
  left: 5px;
  top: 10px;
  color: #555;
}
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>