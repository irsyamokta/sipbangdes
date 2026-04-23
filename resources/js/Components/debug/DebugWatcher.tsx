import { usePage } from "@inertiajs/react";
import { useEffect } from "react";
import { useDebug } from "@/Context/DebugContext";

export default function DebugWatcher() {
  const { props } = usePage();
  const { addLog } = useDebug();

  useEffect(() => {
    if (props.errors && Object.keys(props.errors).length > 0) {
      addLog({
        type: "validation",
        title: "Validation Error",
        data: props.errors,
      });
    }
  }, [props.errors]);

  return null;
}