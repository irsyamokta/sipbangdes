import { useState, useRef, useEffect } from "react";
import { useDebug } from "@/Context/DebugContext";

export default function DebugPanel() {
  const { logs, clearLogs } = useDebug();
  const [open, setOpen] = useState(false);
  const [expandedId, setExpandedId] = useState<string | null>(null);

  const panelRef = useRef<HTMLDivElement | null>(null);

  if (import.meta.env.PROD) return null;

  useEffect(() => {
    function handleClickOutside(event: MouseEvent) {
      if (
        panelRef.current &&
        !panelRef.current.contains(event.target as Node)
      ) {
        setOpen(false);
        setExpandedId(null);
      }
    }

    if (open) {
      document.addEventListener("mousedown", handleClickOutside);
    }

    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, [open]);

  return (
    <div className="fixed bottom-2 right-2 z-50 text-xs">

      {/* Toggle Button */}
      <button
        onClick={() => setOpen((prev) => !prev)}
        className="bg-black text-white px-3 py-2 rounded shadow"
      >
        Debug ({logs.length})
      </button>

      {/* Panel */}
      {open && (
        <div
          ref={panelRef}
          className="mt-2 w-[350px] max-h-[400px] overflow-y-auto bg-gray-900 text-white rounded shadow-lg"
        >
          {/* Header */}
          <div className="flex justify-between items-center p-2 border-b border-gray-700">
            <span>Logs</span>
            <button onClick={clearLogs} className="text-red-400">
              Clear
            </button>
          </div>

          {/* Log List */}
          {logs.length === 0 && (
            <div className="p-2 text-gray-400">No logs</div>
          )}

          {logs.map((log) => (
            <div
              key={log.id}
              className="border-b border-gray-800 p-2 cursor-pointer hover:bg-gray-800"
              onClick={() =>
                setExpandedId(expandedId === log.id ? null : log.id)
              }
            >
              <div className="flex justify-between">
                <span
                  className={
                    log.type === "error"
                      ? "text-red-400"
                      : log.type === "api"
                      ? "text-blue-400"
                      : log.type === "validation"
                      ? "text-yellow-400"
                      : "text-green-400"
                  }
                >
                  {log.title}
                </span>

                <span className="text-gray-400">
                  {new Date(log.timestamp).toLocaleTimeString()}
                </span>
              </div>

              {/* Expanded Data */}
              {expandedId === log.id && (
                <pre className="mt-2 text-gray-300 whitespace-pre-wrap wrap-break-word">
                  {JSON.stringify(log.data, null, 2)}
                </pre>
              )}
            </div>
          ))}
        </div>
      )}
    </div>
  );
}