const parseInsight = (text?: string) => {
    if (!text) return [];

    return text
        .split(/##\s+/)
        .filter(Boolean)
        .map((section) => {
            const titleMatch = section.match(/^([^\n]+)/);
            const title = titleMatch ? titleMatch[1].trim() : "";

            let content = section.replace(title, "").trim();

            const points = content
                .split(/(?:^|\n)(?:-\s+|\d+\.\s+)/)
                .map((p) => p.trim())
                .filter(Boolean);

            return { title, points };
        });
};

const renderBoldText = (text: string) => {
    const parts = text.split(/(\*\*.*?\*\*)/g);

    return parts.map((part, i) => {
        if (part.startsWith("**") && part.endsWith("**")) {
            return (
                <strong key={i} >
                    {part.replace(/\*\*/g, "")}
                </strong>
            );
        }
        return <span key={i}> {part} </span>;
    });
};

export { parseInsight, renderBoldText };
